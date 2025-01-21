<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources;

use Filament\Forms\Components\Component;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource as FilamentResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Modules\Xot\Actions\ModelClass\CountAction;
use Modules\Xot\Filament\Traits\NavigationLabelTrait;

use function Safe\glob;

use Webmozart\Assert\Assert;

abstract class XotBaseResource extends FilamentResource
{
    use NavigationLabelTrait;

    protected static ?string $model = null;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = null;
    protected static ?string $activeNavigationIcon = 'heroicon-s-document-text';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationGroup = 'Parametri di Sistema';
    protected static ?int $navigationSort = 100;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getModuleName(): string
    {
        return Str::between(static::class, 'Modules\\', '\Filament');
    }

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }

    public static function getNavigationLabel(): string
    {
        return static::$modelLabel ?? class_basename(static::getModel());
    }

    public static function getModelLabel(): string
    {
        return static::$modelLabel ?? static::getPluralModelLabel();
    }

    public static function getPluralModelLabel(): string
    {
        /** @var Model $model */
        $model = app(static::getModel());
        Assert::isInstanceOf($model, Model::class);

        return $model->getTable();
    }

    /**
     * Get the model class.
     *
     * @return class-string<Model>
     */
    public static function getModel(): string
    {
        if (null === static::$model) {
            throw new \RuntimeException('Model not defined for resource '.static::class);
        }

        /* @var class-string<Model> */
        return static::$model;
    }

    /**
     * Get form schema.
     *
     * @return array<string, Component>
     */
    public static function getFormSchema(): array
    {
        return [];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(static::getFormSchema());
    }

    /**
     * Get table callback extensions.
     *
     * @return array<string, callable(mixed): mixed>
     */
    public static function extendTableCallback(): array
    {
        return [];
    }

    /**
     * Get form callback extensions.
     *
     * @return array<string, callable(mixed): mixed>
     */
    public static function extendFormCallback(): array
    {
        return [];
    }

    public static function getNavigationBadge(): ?string
    {
        try {
            $count = app(CountAction::class)->execute(static::getModel());

            return number_format($count, 0);
        } catch (\Exception $e) {
            return '--';
        }
    }

    /**
     * Get the resource pages.
     *
     * @return array<string, PageRegistration>
     */
    public static function getPages(): array
    {
        $prefix = static::class.'\Pages\\';
        $name = Str::of(class_basename(static::class))
            ->before('Resource')
            ->toString();

        /** @var array<string, PageRegistration> $pages */
        $pages = [];

        $index = $prefix.'List'.$name.'s';
        $create = $prefix.'Create'.$name;
        $edit = $prefix.'Edit'.$name;

        if (class_exists($index)) {
            /* @var class-string $index */
            $pages['index'] = $index::route('/');
        }
        if (class_exists($create)) {
            /* @var class-string $create */
            $pages['create'] = $create::route('/create');
        }
        if (class_exists($edit)) {
            /* @var class-string $edit */
            $pages['edit'] = $edit::route('/{record}/edit');
        }

        /* @var array<string, PageRegistration> */
        return $pages;
    }

    /**
     * Get the resource relations.
     *
     * @return array<int, class-string>
     */
    public static function getRelations(): array
    {
        $reflector = new \ReflectionClass(static::class);
        $filename = $reflector->getFileName();
        Assert::notNull($filename, 'Cannot get filename from reflection');
        Assert::string($filename);

        $path = Str::of($filename)
            ->before('.php')
            ->append(DIRECTORY_SEPARATOR.'RelationManagers')
            ->toString();

        /** @var array<string> $files */
        $files = glob($path.DIRECTORY_SEPARATOR.'*RelationManager.php') ?: [];
        /** @var array<int, class-string> $relations */
        $relations = [];

        foreach ($files as $file) {
            $info = pathinfo($file);
            /** @var class-string $relationClass */
            $relationClass = static::class.'\RelationManagers\\'.$info['filename'];
            if (class_exists($relationClass)) {
                $relations[] = $relationClass;
            }
        }

        return $relations;
    }
}
