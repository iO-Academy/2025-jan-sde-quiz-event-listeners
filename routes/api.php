<?php

use App\Http\Controllers\AnswerApiController;
use App\Http\Controllers\QuestionApiController;
use App\Http\Controllers\QuizApiController;
use Illuminate\Support\Facades\Route;

route::controller(QuizApiController::class)->group(function () {
    route::get('/quizzes', 'all');
    route::get('/quizzes/{quiz}', 'find');
    route::post('/quizzes', 'create');
    route::put('/quizzes/{quiz}', 'edit');
});

route::post('/questions', [QuestionApiController::class, 'create']);
route::put('/questions/{question}', [QuestionApiController::class, 'edit']);
route::delete('/questions/{question}', [QuestionApiController::class, 'delete']);
route::post('/answers', [AnswerApiController::class, 'create']);
