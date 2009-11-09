#!/bin/bash

#Директория проекта
PROJECT_DIR='/home/deim/workspace/php/mulanix/'
echo 'PROJECT_DIR: '$PROJECT_DIR

#------------------------------------------------------------------------------
#----------------------------Тестирование--------------------------------------
#------------------------------------------------------------------------------
#Директория, где лежат тесты
PHPUNIT_TESTS=$PROJECT_DIR'test/Mnix'

#------------------------------------------------------------------------------
#----------------------------Анализ-покрытия-----------------------------------
#------------------------------------------------------------------------------
#Директория для отчета
PHPUNIT_COVERAGE=$PROJECT_DIR'tmp/doc/coverage'

#------------------------------------------------------------------------------
#----------------------------Документирование----------------------------------
#------------------------------------------------------------------------------
#Директория, куда будет записана документация
PHPDOC_TARGET=$PROJECT_DIR'tmp/doc/api'

#Директории, для сканирования, разделяются запятыми: dir1,dir2,...
#PHPDOC_DIRS=$PROJECT_DIR'lib/Mnix,'$PROJECT_DIR'test/Mnix'
PHPDOC_DIRS=$PROJECT_DIR'lib/Mnix/Core,'$PROJECT_DIR'test/Mnix/Core'

#Файлы, для сканирования, разделяются запятыми: file1,file2,...
#PHPDOC_FILES=$PROJECT_DIR'test/Helper.php'
PHPDOC_FILES=$PROJECT_DIR'lib/Mnix/Core.php,'$PROJECT_DIR'test/Helper.php,'$PROJECT_DIR'test/Mnix/CoreTest.php,'$PROJECT_DIR'test/Mnix/CoreSub.php,'$PROJECT_DIR'lib/Mnix/Cache.php,'$PROJECT_DIR'test/Mnix/CacheSub.php,'$PROJECT_DIR'test/Mnix/CacheTest.php'

#Стиль
#PHPDOC_STYLE='HTML:frames:DOM/earthli'
PHPDOC_STYLE='HTML:Smarty:HandS'
#Файлы, которые будут игнорироваться
PHPDOC_IGNORE='*.xsl'
#Теги, которые будут игнорироваться
PHPDOC_IGNORE_TAG='@dataProvider'
#Заголовок к документации
PHPDOC_TITLE='Mulanix'
#Исходный код в документации
PHPDOC_SOURCE='on'
#Предупреждение о недокументированных элементах
PHPDOC_UE='on'
#Пакет по умолчанию
PHPDOC_PACKAGE='Mnix'
#Категория по умолчанию
PHPDOC_CATEGORY='Mulanix'

#------------------------------------------------------------------------------
#----------------------------Запуск скриптов-----------------------------------
#------------------------------------------------------------------------------
if [[ $1 = '-unit' || $1 = '' ]]
then
    #Запуск phpunit-тестирования
    phpunit $PHPUNIT_TESTS
fi

if [[ $1 = '-doc' || $1 = '' ]]
then
    #Запуск документирования
    #Предварительно очищаем директорию, куда будут записаны тесты, и создаём новую
    rm -r $PHPDOC_TARGET
    mkdir $PHPDOC_TARGET
    PHPDOC_TARGET='-t '$PHPDOC_TARGET
    PHPDOC_DIRS='-d '$PHPDOC_DIRS
    PHPDOC_FILES='-f '$PHPDOC_FILES
    PHPDOC_STYLE='-o '$PHPDOC_STYLE
    PHPDOC_IGNORE='-i '$PHPDOC_IGNORE
    PHPDOC_IGNORE_TAG="-it $PHPDOC_IGNORE_TAG"
    PHPDOC_TITLE="-ti $PHPDOC_TITLE"
    PHPDOC_SOURCE="-s $PHPDOC_SOURCE"
    PHPDOC_UE="-ue $PHPDOC_UE"
    PHPDOC_PACKAGE="-dn $PHPDOC_PACKAGE"
    PHPDOC_CATEGORY="-dc $PHPDOC_CATEGORY"
    phpdoc $PHPDOC_DIRS $PHPDOC_FILES $PHPDOC_STYLE $PHPDOC_TARGET $PHPDOC_IGNORE $PHPDOC_IGNORE_TAG $PHPDOC_TITLE $PHPDOC_SOURCE $PHPDOC_UE $PHPDOC_PACKAGE $PHPDOC_CATEGORY
fi

if [[ $1 = '-cov' || $1 = '' ]]
then
    #Запуск сoverage
    #Предварительно очищаем директорию, куда будут записаны тесты, и создаём новую
    rm -r $PHPUNIT_COVERAGE
    mkdir $PHPUNIT_COVERAGE
    phpunit --coverage-html $PHPUNIT_COVERAGE $PHPUNIT_TESTS'/CacheTest.php'
fi

if [[ $1 != '-unit' && $1 != '' && $1 != '-doc' && $1 != '-cov' ]]
then 
    echo 'Ошибка в параметре!'
fi
