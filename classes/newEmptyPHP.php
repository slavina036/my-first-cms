<?php


/**
 * Класс для обработки статей
 */
class Article
{
    // Свойства
    /**
    * @var int ID статей из базы данны
    */
    public $id = null;

    /**
    * @var int Дата первой публикации статьи
    */
    public $publicationDate = null;

    /**
    * @var string Полное название статьи
    */
    public $title = null;

     /**
    * @var int ID категории статьи
    */
    public $categoryId = null;

     /**
    * @var int ID подкатегории статьи
    */
    public $subcategoryId = null;

    /**
    * @var string Краткое описание статьи
    */

    public $summary = null;

    /**
    * @var string HTML содержание статьи
    */
    public $content = null;

    /**
    * Список авторов статьи
    */
    public array $authors = [];

    /**
    * Количество уникальных просмотров статьи
    */
    public $uniqueViews = null;

    /**
    * Общее количество просмотров статьи
    */
    public $allViews = null;

    /**
    *Активность
    */
    public ?int $active = null;

    /**
    *Следующая сатья
    */
    public ?int $nextArticleId = null;

    /**
    *Предыдущая сатья
    */
    public ?int $previousArticleId = null;



    /**
     * Создаст объект статьи
     *
     * @param array $data массив значений (столбцов) строки таблицы статей
     */
    public function __construct($data=array())
    {

        if (isset($data['id'])) {
            $this->id = (int) $data['id'];
        }

        if (isset( $data['publicationDate'])) {
            $this->publicationDate = (string) $data['publicationDate'];
        }

        if (isset($data['title'])) {
            $this->title = $data['title'];
        }

        if (isset($data['categoryId'])) {
            $this->categoryId = (int) $data['categoryId'];
        }

        if (isset($data['subcategoryId'])) {
            $this->subcategoryId = (int) $data['subcategoryId'];
        }

        if (isset($data['summary'])) {
            $this->summary = $data['summary'];
        }

        if (isset($data['content'])) {
            $this->content = $data['content'];
        }

        if (isset($data['authors'])) {
            $this->authors = (array) $data['authors'];
        }

        if (isset($data['unique_views'])) {
            $this->uniqueViews = $data['unique_views'];
        }

        if (isset($data['all_views'])) {
            $this->allViews = $data['all_views'];
        }

        $this->active = !empty($data['active']);

        if (isset($data['previousArticleId'])) {
            $this->previousArticleId = $data['previousArticleId'];
        }

        if (isset($data['nextArticleId'])) {
            $this->nextArticleId = $data['nextArticleId'];
        }

        if (isset($data['listArticlesIdTitle'])) {
            $this->listArticlesIdTitle = (array) $data['listArticlesIdTitle'];
        }
    }?>