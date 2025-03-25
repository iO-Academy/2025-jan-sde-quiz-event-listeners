<?php

use App\Http\Controllers\QuestionApiController;
use App\Http\Controllers\QuizApiController;
use Illuminate\Support\Facades\Route;

route::get('/quizzes', [QuizApiController::class, 'all']);
route::get('/quizzes/{quiz}', [QuizApiController::class, 'find']);
route::controller(QuizApiController::class)->group(function () {
    route::get('/quizzes', 'all');
    route::post('/quizzes', 'create');
});
route::post('/questions', [QuestionApiController::class, 'create']);
