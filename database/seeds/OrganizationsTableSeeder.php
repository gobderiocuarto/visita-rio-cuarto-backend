<?php

use Illuminate\Database\Seeder;

use Caffeinated\Shinobi\Models\Permission;

class OrganizationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        # Organizaciones
        Permission::create([
            'name'          => 'Organización :: Listar',
            'slug'          => 'organizations.index',
            'description'   => 'Lista y navega todas las organizaciones',
        ]);

        Permission::create([
            'name'          => 'Organización :: Crear',
            'slug'          => 'organizations.create',
            'description'   => 'Podrá crear una organización',            
        ]);

        Permission::create([
            'name'          => 'Organización :: Ver detalle',
            'slug'          => 'organizations.show',
            'description'   => 'Podrá ver el detalle de una organización dado',            
        ]);
        
        Permission::create([
            'name'          => 'Organización :: Edición',
            'slug'          => 'organizations.edit',
            'description'   => 'Podrá editar cualquier dato de una organización',
        ]);
        
        Permission::create([
            'name'          => 'Organización :: Eliminar',
            'slug'          => 'organizations.destroy',
            'description'   => 'Podrá eliminar una organización',      
        ]);


        Permission::create([
            'name'          => 'Organización :: Asociar ubicación',
            'slug'          => 'organizations.storePlace',
            'description'   => 'Podrá crear / editar una ubicación asociado a la organización',      
        ]);

        Permission::create([
            'name'          => 'Organización :: Eliminar ubicación',
            'slug'          => 'organizations.destroyPlace',
            'description'   => 'Podrá eliminar una ubicación asociado a la organización',      
        ]);

    }
}
