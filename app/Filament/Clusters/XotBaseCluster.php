<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Clusters;

<<<<<<< HEAD
use Illuminate\Support\Facades\Lang;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Clusters\Cluster as FilamentCluster;
use Modules\Xot\Filament\Traits\NavigationLabelTrait;

class XotBaseCluster extends FilamentCluster
{
    use NavigationLabelTrait;

    /*
    public static function getNavigationGroup(): ?string
    {

        return 'ZZZZZZZZZZZZZZZZZZ';
    }
    */

    public function getTitle(): Htmlable|string
    {
        $key = static::getKeyTransFunc(__FUNCTION__);
        $res = static::transFunc(__FUNCTION__);
        dddx([
            'key' => $key,
            'res' => $res,
        ]);
        //return Lang::get('broker::cliente.navigation_group');
        return 'AAAAAAAAA';
    }


    /*
    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getNavigationLabel(): string
    {
        //return Lang::get('broker::cliente.cluster.label');
        return 'ZZZZZZZZZZZZZZZZZZ';
    }



    public static function getNavigationSort(): ?int
    {
        //return (int) Lang::get('broker::cliente.navigation_sort');
        return 1;
    }

    public static function getNavigationBadge(): ?string
    {
        return null;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClientes::route('/'),
            'brain' => Pages\ListaBrain::route('/brain'),
        ];
    }
    */
=======
use Filament\Clusters\Cluster;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

/**
 * Base class for all Filament Clusters in the Laraxot framework.
 * 
 * This class extends Filament's Cluster and provides standardized
 * functionality for navigation, translations, and common operations.
 * All concrete Cluster classes should extend this class instead of
 * extending Filament's Cluster directly.
 */
abstract class XotBaseCluster extends Cluster
{
    /**
     * Get the navigation label for this cluster.
     * 
     * Retrieves the label from the translation files based on the cluster's name.
     * Falls back to a default label if no translation is found.
     */
    public static function getNavigationLabel(): string
    {
        $key = static::getTranslationKey() . '.cluster.label';
        
        return Lang::has($key) ? Lang::get($key) : static::getDefaultNavigationLabel();
    }

    /**
     * Get the navigation group for this cluster.
     * 
     * Retrieves the group from the translation files based on the cluster's name.
     * Returns null if no translation is found.
     */
    public static function getNavigationGroup(): ?string
    {
        $key = static::getTranslationKey() . '.navigation_group';
        
        return Lang::has($key) ? Lang::get($key) : null;
    }

    /**
     * Get the navigation sort order for this cluster.
     * 
     * Retrieves the sort order from the translation files based on the cluster's name.
     * Returns null if no translation is found.
     */
    public static function getNavigationSort(): ?int
    {
        $key = static::getTranslationKey() . '.navigation_sort';
        
        if (Lang::has($key)) {
            $value = Lang::get($key);
            return is_numeric($value) ? (int) $value : null;
        }
        
        return null;
    }

    /**
     * Get the navigation icon for this cluster.
     * 
     * Retrieves the icon from the translation files based on the cluster's name.
     * Falls back to a default icon if no translation is found.
     */
    public static function getNavigationIcon(): ?string
    {
        $key = static::getTranslationKey() . '.cluster.icon';
        
        return Lang::has($key) ? Lang::get($key) : 'heroicon-o-squares-2x2';
    }

    /**
     * Get the navigation badge for this cluster.
     * 
     * Retrieves the badge from the translation files based on the cluster's name.
     * Returns null if no translation is found.
     */
    public static function getNavigationBadge(): ?string
    {
        $key = static::getTranslationKey() . '.cluster.badge';
        
        return Lang::has($key) ? Lang::get($key) : null;
    }

    /**
     * Get the default navigation label for this cluster.
     * 
     * Generates a human-readable label from the class name.
     */
    protected static function getDefaultNavigationLabel(): string
    {
        return Str::title(Str::snake(\str_replace('Cluster', '', \class_basename(static::class)), ' '));
    }

    /**
     * Get the translation key for this cluster.
     * 
     * Generates a translation key based on the module name and the cluster name.
     */
    protected static function getTranslationKey(): string
    {
        $classNameParts = \explode('\\', static::class);
        $module = Str::lower($classNameParts[1] ?? '');
        $name = Str::snake(\str_replace('Cluster', '', \class_basename(static::class)));
        
        return "{$module}::{$name}";
    }
>>>>>>> 3f714913 (.)
}
