<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {


        \Validator::extend("tags_rule", function($attribute, $tags, $parameters) {
            $array_tags = explode(',', $tags);

            $rules = [
                'tag' => 'string',
            ];
            
            if ($array_tags) {
                foreach ($array_tags as $tag) {
                    $data = [
                        'tag' => trim($tag)
                    ];
                    $validator = \Validator::make($data, $rules);
                    if ($validator->fails()) {
                        return false;
                    }
                }
                return true;
            }
        });
                
    }
}
