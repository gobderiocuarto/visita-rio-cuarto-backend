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
        
        # --------------------------------------------------------
        # Spaces
        # --------------------------------------------------------

        Permission::create([
            'name'          => 'Espacio :: Listar',
            'slug'          => 'spaces.index',
            'description'   => 'Lista y navega todos los espacios',
        ]);

        Permission::create([
            'name'          => 'Espacio :: Creación',
            'slug'          => 'spaces.create',
            'description'   => 'Podrá crear un espacio',
        ]);
        
        Permission::create([
            'name'          => 'Espacio :: Edición',
            'slug'          => 'spaces.edit',
            'description'   => 'Podría editar cualquier dato de un espacio',
        ]);
        
        Permission::create([
            'name'          => 'Espacio :: Eliminar',
            'slug'          => 'spaces.destroy',
            'description'   => 'Podría eliminar un espacio',      
        ]);
        # END Spaces



        # --------------------------------------------------------
        # Categories
        # --------------------------------------------------------


        Permission::create([
            'name'          => 'Categoría :: Listar',
            'slug'          => 'categories.index',
            'description'   => 'Lista y navega todas las categorías',
        ]);

        Permission::create([
            'name'          => 'Categoría :: Creación',
            'slug'          => 'categories.create',
            'description'   => 'Podrá crear una categoría',
        ]);
        
        Permission::create([
            'name'          => 'Categoría :: Edición',
            'slug'          => 'categories.edit',
            'description'   => 'Podrá editar cualquier dato de una categoría',
        ]);
        
        Permission::create([
            'name'          => 'Categoría :: Eliminar',
            'slug'          => 'categories.destroy',
            'description'   => 'Podrá eliminar una categoría',      
        ]);
        # END Categories




        # --------------------------------------------------------
        # Etiquetas
        # --------------------------------------------------------


        Permission::create([
            'name'          => 'Etiqueta :: Listar',
            'slug'          => 'services.index',
            'description'   => 'Lista y navega todos las etiquetas',
        ]);
        
        Permission::create([
            'name'          => 'Etiqueta :: Edición',
            'slug'          => 'services.edit',
            'description'   => 'Podría editar cualquier dato de una etiqueta',
        ]);
        
        Permission::create([
            'name'          => 'Etiqueta :: Eliminar',
            'slug'          => 'services.destroy',
            'description'   => 'Podría eliminar una etiqueta',      
        ]);

        Permission::create([
            'name'          => 'Etiqueta :: Asociar organización',
            'slug'          => 'services.storeOrganization',
            'description'   => 'Podrá asociar una organización a una etiqueta',      
        ]);

        Permission::create([
            'name'          => 'Etiqueta :: Desvincular organización',
            'slug'          => 'services.unlinkOrganization',
            'description'   => 'Podrá desvincular una organización asociado a la etiqueta',      
        ]);

        Permission::create([
            'name'          => 'Etiqueta :: Asociar espacio',
            'slug'          => 'services.storeSpace',
            'description'   => 'Podrá asociar un espacio a una etiqueta',      
        ]);

        Permission::create([
            'name'          => 'Etiqueta :: Desvincular espacio',
            'slug'          => 'services.unlinkSpace',
            'description'   => 'Podrá desvincular un espacio asociado a la etiqueta',      
        ]);

        # END Etiquetas



        # --------------------------------------------------------
        # Organizaciones
        # --------------------------------------------------------

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

        # END Organizaciones



        # --------------------------------------------------------
        # Events
        # --------------------------------------------------------


        Permission::create([
            'name'          => 'Evento :: Listar',
            'slug'          => 'events.index',
            'description'   => 'Lista y navega todos los eventos',
        ]);

        Permission::create([
            'name'          => 'Evento :: Crear',
            'slug'          => 'events.create',
            'description'   => 'Podrá crear un evento',            
        ]);

        Permission::create([
            'name'          => 'Evento :: Ver detalle',
            'slug'          => 'events.show',
            'description'   => 'Podrá ver el detalle de un evento dado',            
        ]);
        
        Permission::create([
            'name'          => 'Evento :: Edición',
            'slug'          => 'events.edit',
            'description'   => 'Podrá editar cualquier dato de un evento (propio)',
        ]);
        
        Permission::create([
            'name'          => 'Evento :: Eliminar',
            'slug'          => 'events.destroy',
            'description'   => 'Podrá eliminar un evento (propio)',      
        ]);



        Permission::create([
            'name'          => 'Evento :: Cargar imagen',
            'slug'          => 'events.loadImageEvent',
            'description'   => 'Podrá cargar una imagen asociada a evento',      
        ]);

        Permission::create([
            'name'          => 'Evento :: Borrar imagen',
            'slug'          => 'events.destroyImageEvent',
            'description'   => 'Podrá borrar la imagen asociada a evento',      
        ]);

        Permission::create([
            'name'          => 'Evento :: Asociar calendario',
            'slug'          => 'events.saveEventCalendar',
            'description'   => 'Podrá crear / editar calendario asociado a evento',      
        ]);

        Permission::create([
            'name'          => 'Evento :: Eliminar calendario',
            'slug'          => 'events.destroyEventCalendar',
            'description'   => 'Podrá eliminar calendario asociado a evento',      
        ]);
        # END Eventos


    }
}