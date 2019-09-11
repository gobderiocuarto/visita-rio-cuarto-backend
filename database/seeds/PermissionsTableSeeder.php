<?php

use Illuminate\Database\Seeder;
use Caffeinated\Shinobi\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::Create ([
                'name' => 'Listar Eventos',
                'slug' => 'events.index',
                'description' => 'Puede listar y navegar eventos'
        ]);
        Permission::Create ([
                'name' => 'Crear Evento',
                'slug' => 'events.create',
                'description' => 'Puede crear un Evento'
        ]);
        Permission::Create ([
                'name' => 'Ver Evento',
                'slug' => 'events.show',
                'description' => 'Puede ver el detalle de un Evento'
        ]);
        Permission::Create ([
                'name' => 'Editar Evento',
                'slug' => 'events.edit',
                'description' => 'Puede editar el detalle de un Evento'
        ]);
        Permission::Create ([
                'name' => 'Eliminar Evento',
                'slug' => 'events.destroy',
                'description' => 'Puede eliminar un Evento'
        ]);
    }
}
