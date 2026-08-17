<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'estimates',  'middleware' => 'auth'], function() {
    Route::group(['prefix'=> 'projects'], function(){
    Route::get('/estimates/ajax/activity/search','EstimateManagerController@searchMainActivity')->name('estimates.ajax.activity.search');
    Route::get('/estimates/ajax/full-activity-structure','EstimateManagerController@getFullActivityStructure')->name('estimates.ajax.get.full.activity.structure');
    Route::post('/save-activity-structure', 'EstimateManagerController@storeOrUpdateActivityStructure')->name('save.activity.structure');
    Route::post('/duplicate-main-activity', 'EstimateManagerController@duplicateMainActivityDb')->name('duplicate.main.activity.db');



       //Route::get('/{id?}/{default?}', 'EstimateManagerController@projectEstimates')->name('estimates.projects');
        Route::get('/{id}/excel-expanded', 'EstimateManagerController@projectEstimateExcelExpanded')->name('estimates.excel.expanded');
        Route::get('/{id}/excel-collapsed', 'EstimateManagerController@projectEstimateExcelCollapsed')->name('estimates.excel.collapsed');
        Route::get('/{id}/copy-project', 'EstimateManagerController@copyProject')->name('estimates.copy.project');
        Route::get('/{id}/save-project', 'EstimateManagerController@saveProject')->name('estimates.save.project');
        Route::post('/update-estimate-and-project', 'EstimateManagerController@updateEstimateAndProject')->name('estimates.projects.update.detail');
        Route::post('/estimate_library', 'EstimateManagerController@copytolibrary')->name('estimates.copy.estimate_library');
       Route::get('/{id?}/{default?}', 'EstimateManagerController@projectEstimates')->name('estimates.projects');
    });
    
  
    
    
    Route::group(['prefix'=> 'import'], function(){
        Route::get('/activities', 'EstimateManagerController@importProjectEstimateView')->name('estimates.projects.import.view');
        Route::post('/activities', 'EstimateManagerController@importProjectEstimate')->name('estimates.projects.import');
    });

   Route::group(['prefix'=> 'ajax'], function(){
        Route::get('/add-main-activity_row/{id}', 'EstimateManagerAjaxController@addMainActivityRow')->name('estimates.ajax.add.main.activity.row');
        Route::get('/add-sub-activity_row/{id}', 'EstimateManagerAjaxController@addSubActivityRow')->name('estimates.ajax.add.sub.activity.row');
        Route::get('/add-activity_row/{id}', 'EstimateManagerAjaxController@addActivityRow')->name('estimates.ajax.add.activity.row');
        Route::get('/delete-main-activity_row/{id}', 'EstimateManagerAjaxController@removeMainActivityRow')->name('estimates.ajax.delete.main.activity.row');
        Route::post('/delete-main-activity', 'EstimateManagerAjaxController@removeMainActivity')->name('estimates.ajax.delete.main.activity');
        Route::get('/delete-sub-activity_row/{id}', 'EstimateManagerAjaxController@removeSubActivityRow')->name('estimates.ajax.delete.sub.activity.row');
        Route::get('/delete-activity_row/{id}', 'EstimateManagerAjaxController@removeActivityRow')->name('estimates.ajax.delete.activity.row');
        Route::get('/show-project-estimate-info/{id}', 'EstimateManagerAjaxController@showProjectEstimateDetail')->name('estimates.ajax.project.estimate.detail');
        Route::get('/show-project-formula-info/{id}', 'EstimateManagerAjaxController@showProjectFormulaDetail')->name('estimates.ajax.project.formula.detail');
        Route::get('/add-forumla-row/{id}', 'EstimateManagerAjaxController@addProjectFormulaRow')->name('estimates.ajax.add.project.formula.row');
        Route::get('/remove-forumla-row/{id}', 'EstimateManagerAjaxController@removeProjectFormulaRow')->name('estimates.ajax.remove.project.formula.row');
        Route::post('/update-forumla-row/{id}', 'EstimateManagerAjaxController@updateProjectFormulaRow')->name('estimates.ajax.update.project.formula.row');
        Route::post('/update-main-activity/{id}', 'EstimateManagerAjaxController@updateProjectMainActivityRow')->name('estimates.ajax.update.project.main.activity.row');
        Route::post('/update-sub-activity/{id}', 'EstimateManagerAjaxController@updateProjectSubActivityRow')->name('estimates.ajax.update.project.sub.activity.row');
        Route::post('/update-activity/{id}', 'EstimateManagerAjaxController@updateProjectActivityRow')->name('estimates.ajax.update.project.activity.row');
        Route::post('/update-activity-role', 'EstimateManagerAjaxController@updateProjectActivityRole')->name('estimates.ajax.update.project.activity.role');
    });
});


Route::group(['prefix' => 'library',  'middleware' => 'auth'], function() {
    Route::group(['prefix'=> 'projects'], function(){
       
        Route::post('/lib_estimate', 'LibraryManagerController@copyProject1')->name('library.copy.lib_estimate');
        
        Route::get('/{id}/copy-project', 'LibraryManagerController@copyProject')->name('library.copy.project');
        Route::get('/{id}/save-project', 'LibraryManagerController@saveProject')->name('library.save.project');
        Route::get('/{id}/excel-expanded', 'LibraryManagerController@projectEstimateExcelExpanded')->name('library.excel.expanded');
        Route::get('/{id}/excel-collapsed', 'LibraryManagerController@projectEstimateExcelCollapsed')->name('library.excel.collapsed');
        Route::post('/update-estimate-and-project', 'LibraryManagerController@updateEstimateAndProject')->name('library.projects.update.detail');
        Route::get('/{id?}', 'LibraryManagerController@projectEstimates')->name('library.projects');
    });
    
    Route::group(['prefix'=> 'import'], function(){
        Route::get('/activities', 'LibraryManagerController@importProjectEstimateView')->name('library.projects.import.view');
        Route::post('/activities', 'LibraryManagerController@importProjectEstimate')->name('library.projects.import');
    });
    
    
    Route::group(['prefix'=> 'ajax'], function(){
        Route::get('/add-main-activity_row/{id}', 'LibraryManagerAjaxController@addMainActivityRow')->name('library.ajax.add.main.activity.row');
        Route::get('/add-sub-activity_row/{id}', 'LibraryManagerAjaxController@addSubActivityRow')->name('library.ajax.add.sub.activity.row');
        Route::get('/add-activity_row/{id}', 'LibraryManagerAjaxController@addActivityRow')->name('library.ajax.add.activity.row');
        Route::get('/delete-main-activity_row/{id}', 'LibraryManagerAjaxController@removeMainActivityRow')->name('library.ajax.delete.main.activity.row');
        Route::get('/delete-sub-activity_row/{id}', 'LibraryManagerAjaxController@removeSubActivityRow')->name('library.ajax.delete.sub.activity.row');
        Route::get('/delete-activity_row/{id}', 'LibraryManagerAjaxController@removeActivityRow')->name('library.ajax.delete.activity.row');
        Route::post('/update-main-activity/{id}', 'LibraryManagerAjaxController@updateProjectMainActivityRow')->name('library.ajax.update.project.main.activity.row');
        Route::post('/update-sub-activity/{id}', 'LibraryManagerAjaxController@updateProjectSubActivityRow')->name('library.ajax.update.project.sub.activity.row');
        Route::post('/update-activity/{id}', 'LibraryManagerAjaxController@updateProjectActivityRow')->name('library.ajax.update.project.activity.row');
        Route::post('/delete-main-activity', 'LibraryManagerAjaxController@removeMainActivity')->name('library.ajax.delete.main.activity');
        
        
        
        Route::post('/update-activity-role', 'LibraryManagerAjaxController@updateProjectActivityRole')->name('library.ajax.update.project.activity.role');
        Route::get('/show-project-estimate-info/{id}', 'LibraryManagerAjaxController@showProjectEstimateDetail')->name('library.ajax.project.estimate.detail');
        Route::get('/show-project-formula-info/{id}', 'LibraryManagerAjaxController@showProjectFormulaDetail')->name('library.ajax.project.formula.detail');
        Route::get('/add-forumla-row/{id}', 'LibraryManagerAjaxController@addProjectFormulaRow')->name('library.ajax.add.project.formula.row');
        Route::get('/remove-forumla-row/{id}', 'LibraryManagerAjaxController@removeProjectFormulaRow')->name('library.ajax.remove.project.formula.row');
        Route::post('/update-forumla-row/{id}', 'LibraryManagerAjaxController@updateProjectFormulaRow')->name('library.ajax.update.project.formula.row');
        
        
    });
});


Route::group(['prefix' => 'carbon',  'middleware' => 'auth'], function() {
    Route::group(['prefix'=> 'projects'], function(){
       
        //Route::post('/lib_estimate', 'CarbonManagerController@copyProject2')->name('carbon.copy.lib_estimate');
        
        //Route::get('/{id}/copy-project', 'CarbonManagerController@copyProject')->name('carbon.copy.project');
       // Route::get('/{id}/save-project', 'CarbonManagerController@saveProject')->name('carbon.save.project');
        Route::get('/{id}/excel-expanded', 'CarbonManagerController@projectEstimateExcelExpanded')->name('carbon.excel.expanded');
        Route::get('/{id}/excel-collapsed', 'CarbonManagerController@projectEstimateExcelCollapsed')->name('carbon.excel.collapsed');
       // Route::post('/update-estimate-and-project', 'CarbonManagerController@updateEstimateAndProject')->name('carbon.projects.update.detail');
        Route::get('/{id?}', 'CarbonManagerController@projectEstimates')->name('carbon.projects');
    });
    
    Route::group(['prefix'=> 'import'], function(){
        Route::get('/activities', 'CarbonManagerController@importProjectEstimateView')->name('carbon.projects.import.view');
        Route::post('/activities', 'CarbonManagerController@importProjectEstimate')->name('carbon.projects.import');
    });
    
    
    Route::group(['prefix'=> 'ajax'], function(){
        /*Route::get('/add-main-activity_row/{id}', 'CarbonManagerAjaxController@addMainActivityRow')->name('carbon.ajax.add.main.activity.row');
        Route::get('/add-sub-activity_row/{id}', 'CarbonManagerAjaxController@addSubActivityRow')->name('carbon.ajax.add.sub.activity.row');
        Route::get('/add-activity_row/{id}', 'CarbonManagerAjaxController@addActivityRow')->name('carbon.ajax.add.activity.row');
        Route::get('/delete-main-activity_row/{id}', 'CarbonManagerAjaxController@removeMainActivityRow')->name('carbon.ajax.delete.main.activity.row');
        Route::get('/delete-sub-activity_row/{id}', 'CarbonManagerAjaxController@removeSubActivityRow')->name('carbon.ajax.delete.sub.activity.row');
        Route::get('/delete-activity_row/{id}', 'CarbonManagerAjaxController@removeActivityRow')->name('carbon.ajax.delete.activity.row');
       Route::post('/update-main-activity/{id}', 'CarbonManagerAjaxController@updateProjectMainActivityRow')->name('carbon.ajax.update.project.main.activity.row');
        Route::post('/update-sub-activity/{id}', 'CarbonManagerAjaxController@updateProjectSubActivityRow')->name('carbon.ajax.update.project.sub.activity.row');
        Route::post('/update-activity/{id}', 'CarbonManagerAjaxController@updateProjectActivityRow')->name('carbon.ajax.update.project.activity.row');
        Route::post('/delete-main-activity', 'CarbonManagerAjaxController@removeMainActivity')->name('carbon.ajax.delete.main.activity');
        
        
        
        Route::post('/update-activity-role', 'CarbonManagerAjaxController@updateProjectActivityRole')->name('carbon.ajax.update.project.activity.role');
        Route::get('/show-project-estimate-info/{id}', 'CarbonManagerAjaxController@showProjectEstimateDetail')->name('carbon.ajax.project.estimate.detail');
        Route::get('/show-project-formula-info/{id}', 'CarbonManagerAjaxController@showProjectFormulaDetail')->name('carbon.ajax.project.formula.detail');
        Route::get('/add-forumla-row/{id}', 'CarbonManagerAjaxController@addProjectFormulaRow')->name('carbon.ajax.add.project.formula.row');
        Route::get('/remove-forumla-row/{id}', 'CarbonManagerAjaxController@removeProjectFormulaRow')->name('carbon.ajax.remove.project.formula.row');
        Route::post('/update-forumla-row/{id}', 'CarbonManagerAjaxController@updateProjectFormulaRow')->name('carbon.ajax.update.project.formula.row');*/
        
        
    });
});