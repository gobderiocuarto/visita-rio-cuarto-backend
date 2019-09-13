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
        
        # Categorias
        Permission::create([
            'name'          => 'Listar Categorías',
            'slug'          => 'categories.index',
            'description'   => 'Lista y navega todas las categorías',
        ]);

        Permission::create([
            'name'          => 'Creación de Categorías',
            'slug'          => 'categories.create',
            'description'   => 'Podrá crear una categoría',
        ]);
        
        Permission::create([
            'name'          => 'Edición de Categorías',
            'slug'          => 'categories.edit',
            'description'   => 'Podrá editar cualquier dato de una categoría',
        ]);
        
        Permission::create([
            'name'          => 'Eliminar Categorías',
            'slug'          => 'categories.destroy',
            'description'   => 'Podrá eliminar una categoría',      
        ]);
        # END Categorias




        



        




        # Organizaciones
        Permission::create([
            'name'          => 'Listar Organizaciones',
            'slug'          => 'organizations.index',
            'description'   => 'Lista y navega todos las organizaciones',
        ]);
        
        Permission::create([
            'name'          => 'Edición de Organizaciones',
            'slug'          => 'organizations.edit',
            'description'   => 'Podría editar cualquier dato de una organización',
        ]);
        
        Permission::create([
            'name'          => 'Eliminar organizaciones',
            'slug'          => 'organizations.destroy',
            'description'   => 'Podría eliminar una organización',      
        ]);
        # END Organizaciones




        # Etiquetas
        Permission::create([
            'name'          => 'Listar Etiquetas',
            'slug'          => 'services.index',
            'description'   => 'Lista y navega todos las etiquetas',
        ]);
        
        Permission::create([
            'name'          => 'Edición de Etiquetas',
            'slug'          => 'services.edit',
            'description'   => 'Podría editar cualquier dato de una etiqueta',
        ]);
        
        Permission::create([
            'name'          => 'Eliminar etiquetas',
            'slug'          => 'services.destroy',
            'description'   => 'Podría eliminar una etiqueta',      
        ]);
        # END Etiquetas



        




        # Espacios
        Permission::create([
            'name'          => 'Listar Espacios',
            'slug'          => 'spaces.index',
            'description'   => 'Lista y navega todos los espacios',
        ]);
        
        Permission::create([
            'name'          => 'Edición de Espacios',
            'slug'          => 'spaces.edit',
            'description'   => 'Podría editar cualquier dato de un espacio',
        ]);
        
        Permission::create([
            'name'          => 'Eliminar Espacios',
            'slug'          => 'spaces.destroy',
            'description'   => 'Podría eliminar un espacio',      
        ]);
        # END Espacios


    }
}