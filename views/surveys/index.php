<?php

use models\User;
?>
    <div class="page-wrapper">
    <header id="header"><!--header-->
        <div class="header-middle"><!--header-middle-->
            <div class="container">
                <div class="row align-items-center">
                    <h1>Сервис агрегатор для проведения фиктивных опросов</h1>
                    <div class="authenticationBlock">
                            <ul class="nav navbar-nav">
                                <?php if (User::isGuest()):?>
                                    <p class="greeting">Привет Гость!</p>
                                    <li><a href="#modal" data-bs-toggle="modal" data-bs-target="#modal"><i
                                                    class="fa fa-plus-square"></i> Регистрация</a></li>
                                    <li><a href="#modalLogin" data-bs-toggle="modal" data-bs-target="#modalLogin"><i
                                                    class="fa fa-lock"></i> Вход</a></li>
                                <?php
                                else:?>
                                    <p class="greeting"></p>
                                    <li><a href="/cabinet/"><i class="fa fa-user"></i> Личный кабинет</a></li>
                                    <li><a href="/logout/"><i class="fa fa-unlock"></i> Выход</a></li>
                                <?php
                                endif; ?>
                            </ul>
                            <!--Registration modal window-->
                            <div class="modal fade" id="modal" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Регистрация в сервисе</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            <span class="messageShow" style=" display: block;"></span>
                                        </div>
                                        <div class="modal-body">
                                            <div id="registerBox" class="signup-form"><!--sign up form-->
                                                <form action="" id="register" method="post" role="form">
                                                    <div class="form-group">
                                                        <label for="name">Имя<span class="error">*</span></label>
                                                        <input type="text" name="name" class="form-control" id="name"
                                                               placeholder="Введите имя"
                                                               value="" required/>
                                                        <p class="help-block">Например: John</p>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="email">Email<span class="error">*</span></label>
                                                        <input type="email" name="email" class="form-control" id="email"
                                                               placeholder="Введите email"
                                                               value="" required/>
                                                        <p class="help-block">Например: example@gmail.com</p>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="password">Пароль<span class="error">*</span></label>
                                                        <label for="pwd"></label><input type="password" name="password" class="form-control"
                                                                                        id="password"
                                                                                        placeholder="Введите пароль" value="" required/>
                                                        <p class="help-block">Не меньше шести символов </p>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="pwd2">Повторите пароль<span
                                                                    class="error">*</span></label>
                                                        <input type="password" name="pwd2" class="form-control"
                                                               id="pwd2"
                                                               placeholder="Введите пароль" value="" required/>
                                                    </div>
                                                        <input type="submit" name="submit" id="registerBtn"
                                                           class="btn btn-primary" value="Регистрация"/>
                                                </form>
                                            </div><!--/sign up form-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--Login modal window-->
                            <div class="modal fade" id="modalLogin" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Авторизация в сервисе</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            <span class="messageShow"></span>

                                        </div>
                                        <div class="modal-body">
                                            <div id="login" class="signup-form"><!--sign up form-->
                                                <form action="#" id="login" method="post" role="form">

                                                    <div class="form-group">
                                                        <label for="emailLogin"></label>
                                                        <input type="email" name="email" id="emailLogin"
                                                               class="form-control" placeholder="E-mail"
                                                               value="" required/>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="passwordLogin"></label>
                                                        <input type="password"
                                                               name="password" id="passwordLogin"
                                                               class="form-control"
                                                               placeholder="Пароль"
                                                               value="" required/>
                                                    </div>
                                                        <input type="submit" name="submit" id="loginBtn"
                                                           class="btn btn-primary" value="Вход"/>
                                                </form>
                                            </div><!--/sign up form-->

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div><!--/header-middle-->
    </header><!--/header-->
    </div>

<?php