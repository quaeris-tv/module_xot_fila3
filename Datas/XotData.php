<?php

declare(strict_types=1);

namespace Modules\Xot\Datas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Livewire\Wireable;
use Modules\Tenant\Services\TenantService;
use Modules\User\Models\Membership;
use Modules\User\Models\Team;
use Modules\Xot\Contracts\ProfileContract;
use Spatie\LaravelData\Concerns\WireableData;
use Spatie\LaravelData\Data;
use Webmozart\Assert\Assert;

use function Safe\realpath;

/**
 * Undocumented class.
 */
class XotData extends Data implements Wireable
{
    use WireableData;

    public string $main_module = '';

    // => 'Blog'
    public string $param_name = 'noset';

    public string $adm_home = '01';

    public ?string $adm_theme = ''; // ' => 'AdminLTE',
    // public bool $enable_ads;//' => '1',

    public string $primary_lang = 'it';

    // 'pub_theme' => 'DirectoryBs5',
    public string $pub_theme;

    // ' => 'One',
    public string $search_action = 'it/videos';

    public bool $show_trans_key = false;

    public string $register_type = '0';

    public string $verification_type = '';

    public bool $login_verified = false;

    public bool $disable_frontend_dynamic_route = false;

    public bool $disable_admin_dynamic_route = false;

    public bool $disable_database_notifications = true;

    public bool $register_adm_theme = false;

    public bool $register_pub_theme = false;

    public bool $register_collective = false;

    public string $team_class = 'Modules\User\Models\Team'; // = Team::class;

    public string $tenant_class = 'Modules\User\Models\Tenant'; // = Team::class;

    public string $membership_class = 'Modules\User\Models\Membership'; // = Membership::class;

    public string $tenant_pivot_class = 'Modules\User\Models\TenantUser'; // = Membership::class;

    public ?string $super_admin = null;

    private static ?self $instance = null;

    /**
     * @var (Model&ProfileContract)|null
     */
    private $profile;

    public static function make(): self
    {
        if (! self::$instance) {
            $data = TenantService::getConfig('xra');
            self::$instance = self::from($data);
        }

        return self::$instance;
    }

    public function getUserClass(): string
    {
        Assert::classExists($class = config('auth.providers.users.model'), 'check config auth');

        return $class;
    }

    /**
     * @return class-string
     */
    public static function resolveUserClass(): string
    {
        // Assert class can be created
        $instance = static::make();

        Assert::classExists($res = $instance->getUserClass());

        return $res;
    }

    public function getTeamClass(): string
    {
        return $this->team_class;
    }

    /**
     * Undocumented function.
     */
    public function getTenantClass(): string
    {
        return $this->tenant_class;
    }

    public function getTenantResourceClass(): string
    {
        return Str::of($this->tenant_class)
            ->replace('\Models\\', '\Filament\Resources\\')
            ->append('Resource')
            ->toString();
    }

    public function getTenantPivotClass(): string
    {
        return $this->tenant_pivot_class;
    }

    public function getMembershipClass(): string
    {
        return $this->membership_class;
    }

    public function getProfileClass(): string
    {
        return 'Modules\\'.$this->main_module.'\Models\Profile';
    }

    public function getHomeController(): string
    {
        return 'Modules\\'.$this->main_module.'\Http\Controllers\HomeController';
    }

    public function getProfileModelByUserId(string $user_id): Model&ProfileContract
    {
        $profileClass = $this->getProfileClass();
        $profile = app($profileClass);
        if (! in_array('user_id', $profile->getFillable())) {
            throw new \Exception('add user_id to fillable on class '.$profileClass);
        }

        $res = $profile->firstOrCreate(['user_id' => $user_id]);

        return $res;
    }

    public function getProfileModel(): Model&ProfileContract
    {
        if ($this->profile != null) {
            return $this->profile;
        }
        $user_id = (string) authId();

        Assert::isInstanceOf($this->profile = $this->getProfileModelByUserId($user_id), ProfileContract::class, '['.__LINE__.']['.__FILE__.']');

        return $this->profile;
    }

    public function update(array $data): self
    {
        foreach ($data as $k => $v) {
            $this->{$k} = $v;
        }

        // $this->save();
        return $this;
    }

    public function save(): void
    {
        dddx('wip');
    }

    public function getPubThemeViewPath(string $key = ''): string
    {
        $theme = $this->pub_theme;
        $path0 = base_path('Themes/'.$theme.'/Resources/views/'.$key);
        try {
            $path = realpath($path0);
        } catch (\Exception $e) {
            throw new \Exception('realpath not find dir['.$path0.']'.PHP_EOL.'['.$e->getMessage().']');
        }

        return $path;
    }
}
