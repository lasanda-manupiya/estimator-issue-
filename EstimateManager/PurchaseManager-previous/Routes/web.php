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

Route::group(['middleware' => 'auth'], function() {

    ## Quotations ##
    Route::group(['prefix' => 'quotations', 'as' => 'quotations.'], function() {
        Route::get('/', 'QuotationManagerController@index')->name('index');
        Route::get('/add', 'QuotationManagerController@create')->name('create');
        Route::post('/add', 'QuotationManagerController@store')->name('store');
        Route::get('/view/{id}', 'QuotationManagerController@show')->name('view');
        Route::get('/edit/{id}', 'QuotationManagerController@edit')->name('edit');
        Route::patch('/{id}/update', 'QuotationManagerController@update')->name('update');
        Route::get('/send/{id}/quotation', 'QuotationManagerController@sendQuotation')->name('send.quotation');
        Route::post('/send/{id}/quotation', 'QuotationManagerController@sendQuotationToSuppliers')->name('send.quotation.to.supplier');


        Route::get('/supplier/{id}/quotation', 'QuotationManagerController@supplierQuotation')->name('supplier.quotation');
        Route::post('/supplier/{id}/quotation-reply', 'QuotationManagerController@sendQuotationReplyBySupplier')->name('supplier.quotation.reply');
        Route::post('/admin/{id}/quotation-reply', 'QuotationManagerController@sendQuotationReplyByAdmin')->name('admin.quotation.reply');

    });
    ## Purchase orders ##
    Route::group(['prefix' => 'purchase-orders', 'as' => 'purchase.orders.'], function() {
        Route::get('/', 'PurchaseOrderManagerController@index')->name('index');
        Route::get('/add', 'PurchaseOrderManagerController@create')->name('create');
        Route::post('/add', 'PurchaseOrderManagerController@store')->name('store');
        Route::get('/view/{id}', 'PurchaseOrderManagerController@show')->name('view');
        Route::get('/{id}/history', 'PurchaseOrderManagerController@history')->name('history');
        Route::get('/{id}/print-view', 'PurchaseOrderManagerController@printView')->name('print.view');
        Route::get('/edit/{id}', 'PurchaseOrderManagerController@edit')->name('edit');
        Route::patch('/{id}/update', 'PurchaseOrderManagerController@update')->name('update');
        Route::get('/{id}/delete', 'PurchaseOrderManagerController@destroy')->name('delete');
        Route::get('/approve/invoice/{id?}', 'PurchaseOrderManagerController@approveInvoice')->name('approve.invoice');
        Route::post('/{id}/send-invoice', 'PurchaseOrderManagerController@sendInvoiceMailToSuppliers')->name('send.invoice');
        Route::get('/add-separate', 'PurchaseOrderManagerController@createSeparate')->name('create.separate');
        Route::post('/add-separate', 'PurchaseOrderManagerController@storeSeparate')->name('store.separate');

        Route::get('/supplier/{id}/purchase-order', 'PurchaseOrderManagerController@supplierPurchaseOrder')->name('supplier.purchase.order');
        Route::post('/{id}/update-status', 'PurchaseOrderManagerController@updateStatus')->name('update.status');
    });

    ## Ajax 
    Route::group(['prefix'=> 'ajax', 'as'=> 'ajax.'], function(){

        Route::get('/areas-list', 'CommanAjaxController@getAreas')->name('areas.list');
        Route::get('/levels-list', 'CommanAjaxController@getLevels')->name('levels.list');
        Route::get('/areas-n-levels-list', 'CommanAjaxController@getAreasAndLevels')->name('areas.n.levels.list');
        Route::get('/sub-codes-list', 'CommanAjaxController@getSubCodes')->name('sub.codes.list');

        ## Quotations ##
        Route::group(['prefix' => 'quotations', 'as' => 'quotations.'], function() {
            Route::get('/quotation-form', 'CommanAjaxController@getQuotationForm')->name('quotation.form');
            Route::get('/all/ajax/lists', 'CommanAjaxController@getQuotations')->name('list.all');
        });

        ## Purchase orders ##
        Route::group(['prefix' => 'purchase-orders', 'as' => 'purchase.orders.'], function() {
            Route::get('/purchase-order-form', 'CommanAjaxController@getPurchaseOrderForm')->name('purchase.order.form');
            Route::get('/separate-purchase-order-form', 'CommanAjaxController@getSeparatePurchaseOrderForm')->name('separate.purchase.order.form');
            Route::get('/all/ajax/lists', 'CommanAjaxController@getPurchaseOrders')->name('list.all');
        });
        
    });
});
