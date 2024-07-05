<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrgChartController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'orgchart'], function () {
    Route::post('/add', [OrgChartController::class, 'store']); // will handle full org chat and new member
    Route::post('/update', [OrgChartController::class, 'update']); // will handle the update of the employees details

 });