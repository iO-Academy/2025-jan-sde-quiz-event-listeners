<?php

use App\Http\Controllers\QuizApiController;
use Illuminate\Support\Facades\Route;

route::controller(QuizApiController::class)->group(function () {
    route::get('/quizzes', 'all');
    route::post('/quizzes', 'create');
});
