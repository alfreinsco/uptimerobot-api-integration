<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\MonitorController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/api/dashboard', [DashboardController::class, 'api'])->name('dashboard.api');
Route::get('/api/dashboard/chart', [DashboardController::class, 'chartData'])->name('dashboard.chart');
Route::get('/api/dashboard/chart/week', [DashboardController::class, 'chartDataWeek'])->name('dashboard.chart.week');
Route::get('/api/dashboard/chart/month', [DashboardController::class, 'chartDataMonth'])->name('dashboard.chart.month');
Route::get('/api/dashboard/chart/year', [DashboardController::class, 'chartDataYear'])->name('dashboard.chart.year');
Route::get('/monitors', [MonitorController::class, 'index'])->name('monitors.index');
Route::get('/api/monitors', [MonitorController::class, 'api'])->name('monitors.api');
Route::get('/api/monitors/url-groups', [MonitorController::class, 'urlGroups'])->name('monitors.url-groups');
Route::get('/monitors/export', [MonitorController::class, 'export'])->name('monitors.export');
Route::delete('/monitors/delete-by-date-range', [MonitorController::class, 'deleteByDateRange'])->name('monitors.delete-by-date-range');
Route::get('/exports', [ExportController::class, 'index'])->name('exports.index');
Route::get('/api/exports', [ExportController::class, 'api'])->name('exports.api');
Route::get('/exports/{export}/download', [ExportController::class, 'download'])->name('exports.download');
Route::delete('/exports/{export}', [ExportController::class, 'destroy'])->name('exports.destroy');
