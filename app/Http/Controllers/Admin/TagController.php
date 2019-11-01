<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use \Conner\Tagging\Model\Tag;
use \Conner\Tagging\Model\Tagged;

class TagController extends Controller
{
    
    // ------------------------------------------------------------------------------
    // api/services/{termino?}

    // Obtener colección de nombres de tags agrupados bajo "SERVICIOS"
    // ------------------------------------------------------------------------------

    public function getTagsServices($termino = '')
    {
        $service_tags = Tag::inGroup('Servicios')
        ->where('name', 'LIKE', "%$termino%")->pluck('name');
        return $service_tags;
    }


    // -------------------------------------------------------------------------
    // Obtener colección de nombres de tags asociados a grupo "EVENTOS"
    // -------------------------------------------------------------------------

    public function getTagsEvents($termino = '')
    {
        
        $event_tags = Tag::inGroup('Eventos');
        $event_tags->where('name', 'LIKE', "%$termino%");
        $event_tags = $event_tags->pluck('name');
        return $event_tags;
    }


    // -------------------------------------------------------------------------
    // Obtener colección de nombres de tags NO asociados a grupo "EVENTOS"
    // -------------------------------------------------------------------------

    public function getTagsNoEvents($termino = '')
    {
        $tags_no_events = Tag::where('tagging_tags.name', 'LIKE', "%$termino%")
        ->where( function($query){
            $query->where('tagging_tags.tag_group_id','!=',2)
            ->orWhereNull('tagging_tags.tag_group_id');
        })
        ->distinct()
        ->pluck('name');

        return $tags_no_events;
    }
}
