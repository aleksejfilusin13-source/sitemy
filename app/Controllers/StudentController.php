<?php

final class StudentController
{
    public function index(): void
    {
        require __DIR__ . '/../../include/header.php';
        echo '<h1 class="page__title">Главная страница (Список студентов)</h1>';
        echo '<p>Добро пожаловать в учебный проект Student MVC.</p>';
        require __DIR__ . '/../../include/footer.php';
    }

    public function show(): void { $this->index(); }
    public function create(): void { $this->index(); }
    public function statistics(): void { $this->index(); }
}
