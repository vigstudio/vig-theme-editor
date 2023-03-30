<?php

Route::group(['namespace' => 'VigStudio\VigThemeEditor\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'vig-theme-editors', 'as' => 'vig-theme-editor.'], function () {
            Route::get('/', [
                'as' => 'index',
                'uses' => 'VigThemeEditorController@index',
                'permission' => 'vig-theme-editor',
            ]);

            Route::post('put-content/{id}', [
                'as' => 'put',
                'uses' => 'VigThemeEditorController@putFileContent',
                'permission' => 'vig-theme-editor',
            ]);
        });
    });
});
