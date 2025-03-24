<?php

use App\Http\Controllers\QuizApiController;
use Illuminate\Support\Facades\Route;

route::get('/quizzes', [QuizApiController::class, 'all']);
route::get('/quizzes/{quiz}', [QuizApiController::class, 'find']);
