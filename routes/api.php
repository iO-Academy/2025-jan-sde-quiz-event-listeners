<?php

use App\Http\Controllers\AnswerApiController;
use App\Http\Controllers\QuestionApiController;
use App\Http\Controllers\QuizApiController;
use Illuminate\Support\Facades\Route;

route::controller(QuizApiController::class)->group(function () {
    route::get('/quizzes', 'all');
    route::get('/quizzes/{quiz}', 'find');
    route::post('/quizzes', 'create');
});

route::post('/questions', [QuestionApiController::class, 'create']);
route::post('/answers', [AnswerApiController::class, 'create']);
