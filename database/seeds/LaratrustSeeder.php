<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaratrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return  void
     */
    public function run()
    {
        $this->command->info('Truncating User, Role and Permission tables');
        $this->truncateLaratrustTables();

//        $config = config('laratrust_seeder.role_structure');
//        $userPermission = config('laratrust_seeder.permission_structure');
//        $mapPermission = collect(config('laratrust_seeder.permissions_map'));

//        foreach ($config as $key => $modules) {

            // Create a new role

            $permissions = [];

//            $this->command->info('Creating Role '. strtoupper($key));
//
//            // Reading role permission modules
//            foreach ($modules as $module => $value) {
//
//                foreach (explode(',', $value) as $p => $perm) {
//
//                    $permissionValue = $mapPermission->get($perm);
//
//                    $permissions[] = \App\Permission::firstOrCreate([
//                        'name' => $permissionValue . '-' . $module,
//                        'display_name' => ucfirst($permissionValue) . ' ' . ucfirst($module),
//                        'description' => ucfirst($permissionValue) . ' ' . ucfirst($module),
//                    ])->id;
//
//                    $this->command->info('Creating Permission to '.$permissionValue.' for '. $module);
//                }
//            }

            // Attach all permissions to the role
//            $role->permissions()->sync($permissions);

//            $this->command->info("Creating '{$key}' user");

            // Create default user for each role
//        }
        $role = \App\Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
            'description' => 'Super admin'
        ]);
        $user = \App\User::create([
            'name' => 'Erkin',
            'username' => 'artel0060',
            'password' => bcrypt('Erk30d3353')
        ]);
        $user->attachRole($role);
        $user = \App\User::create([
            'name' => 'Timur',
            'username' => 'artel0354',
            'password' => bcrypt('artel0354')
        ]);
        $user->attachRole($role);
        $user = \App\User::create([
            'name' => 'Shohrux',
            'username' => 'artel0352',
            'password' => bcrypt('artel0352')
        ]);
        $user->attachRole($role);
        $user = \App\User::create([
            'name' => 'Saidmurod',
            'username' => 'artel0425',
            'password' => bcrypt('artel0425')
        ]);
        $user->attachRole($role);
        $user = \App\User::create([
            'name' => 'Sulton',
            'username' => 'artel0385',
            'password' => bcrypt('artel0385')
        ]);
        $user->attachRole($role);

        $role = \App\Role::create([
            'name' => 'clerk',
            'display_name' => 'Кладовщик',
            'description' => 'Материально-ответственное лицо. Его задача – принимать и хранить товарно-материальные ценности со склада и отгружать их в соответствии с расходными документами'
        ]);
        $user = \App\User::create([
            'name' => 'Shuhrat',
            'username' => 'artel4456',
            'password' => bcrypt('clerk@2021')
        ]);
        $user->attachRole($role);

        $role = \App\Role::create([
            'name' => 'manager',
            'display_name' => 'Завсклад',
            'description' => 'Специалист, который руководит работой склада и лично несет ответственность за его функционирование. Без заведующего складом в принципе невозможна грамотная организация складского хозяйства. ... Обеспечение сохранности хранящихся на складе товарно-материальных и иных ценностей.'
        ]);
        $user = \App\User::create([
            'name' => 'Toxir',
            'username' => 'artel6780',
            'password' => bcrypt('manager123')
        ]);
        $user->attachRole($role);

        $role = \App\Role::create([
            'name' => 'laboratorian',
            'display_name' => 'Лаборант',
            'description' => 'Научно-технический сотрудник лаборатории.'
        ]);
        $user = \App\User::create([
            'name' => 'Unknown',
            'username' => 'artel9816',
            'password' => bcrypt('l@b2120')
        ]);
        $user->attachRole($role);

        $role = \App\Role::create([
            'name' => 'requestor',
            'display_name' => 'Requestor',
            'description' => ''
        ]);
        $user = \App\User::create([
            'name' => 'Unknown',
            'username' => 'artel6336',
            'password' => bcrypt('requestor!')
        ]);
        $user->attachRole($role);

        $role = \App\Role::create([
            'name' => 'director',
            'display_name' => 'Director',
            'description' => ''
        ]);
        $user = \App\User::create([
            'name' => 'Abror',
            'username' => 'artel6547',
            'password' => bcrypt('password')
        ]);
        $user->attachRole($role);

        // Creating user with permissions
//        if (!empty($userPermission)) {
//
//            foreach ($userPermission as $key => $modules) {
//
//                foreach ($modules as $module => $value) {
//
//                    // Create default user for each permission set
//                    $user = \App\User::create([
//                        'name' => ucwords(str_replace('_', ' ', $key)),
//                        'email' => $key.'@app.com',
//                        'password' => bcrypt('password'),
//                        'remember_token' => str_random(10),
//                    ]);
//                    $permissions = [];
//
//                    foreach (explode(',', $value) as $p => $perm) {
//
//                        $permissionValue = $mapPermission->get($perm);
//
//                        $permissions[] = \App\Permission::firstOrCreate([
//                            'name' => $permissionValue . '-' . $module,
//                            'display_name' => ucfirst($permissionValue) . ' ' . ucfirst($module),
//                            'description' => ucfirst($permissionValue) . ' ' . ucfirst($module),
//                        ])->id;
//
//                        $this->command->info('Creating Permission to '.$permissionValue.' for '. $module);
//                    }
//                }
//
//                // Attach all permissions to the user
//                $user->permissions()->sync($permissions);
//            }
//        }
    }

    /**
     * Truncates all the laratrust tables and the users table
     *
     * @return    void
     */
    public function truncateLaratrustTables()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('permission_role')->truncate();
        DB::table('permission_user')->truncate();
        DB::table('role_user')->truncate();
        \App\User::truncate();
        \App\Role::truncate();
        \App\Permission::truncate();
        Schema::enableForeignKeyConstraints();
    }
}
