<?php

use Illuminate\Database\Seeder;
use Caffeinated\Shinobi\Models\Permission;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        # Eventos
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
    
    }

}
