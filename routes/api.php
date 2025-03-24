<?php

use App\Http\Controllers\QuizApiController;
use Illuminate\Support\Facades\Route;

route::get('/quizzes', [QuizApiController::class, 'all']);
route::post('/quizzes', [QuizApiController::class, 'create']);
