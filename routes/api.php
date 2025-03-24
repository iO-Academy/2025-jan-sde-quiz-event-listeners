<?php

use App\Http\Controllers\QuestionApiController;
use App\Http\Controllers\QuizApiController;
use Illuminate\Support\Facades\Route;

route::get('/quizzes', [QuizApiController::class, 'all']);
route::post('/questions', [QuestionApiController::class, 'create']);
