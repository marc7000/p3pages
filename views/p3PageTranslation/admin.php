<?php
$this->setPageTitle(
    Yii::t('P3PagesModule.model', 'P3 Page Translations')
    . ' - '
    . Yii::t('P3PagesModule.crud', 'Manage')
);

$this->breadcrumbs[] = Yii::t('P3PagesModule.model', 'P3 Page Translations');
Yii::app()->clientScript->registerScript('search', "
    $('.search-button').click(function(){
        $('.search-form').toggle();
        return false;
    });
    $('.search-form form').submit(function(){
        $.fn.yiiGridView.update(
            'p3-page-translation-grid',
            {data: $(this).serialize()}
        );
        return false;
    });
    ");
?>

<?php $this->widget("TbBreadcrumbs", array("links" => $this->breadcrumbs)) ?>
    <h1>

        <?php echo Yii::t('P3PagesModule.model', 'P3 Page Translations'); ?>
        <small><?php echo Yii::t('P3PagesModule.crud', 'Manage'); ?></small>

    </h1>


<?php $this->renderPartial("_toolbar", array("model" => $model)); ?>
<?php Yii::beginProfile('P3PageTranslation.view.grid'); ?>


<?php
$this->widget('TbGridView',
    array(
        'id' => 'p3-page-translation-grid',
        'dataProvider' => $model->search(),
        'filter' => $model,
        #'responsiveTable' => true,
        'template' => '{summary}{pager}{items}{pager}',
        'pager' => array(
            'class' => 'TbPager',
            'displayFirstAndLast' => true,
        ),
        'columns' => array(
            array(
                'class' => 'CLinkColumn',
                'header' => '',
                'labelExpression' => '$data->itemLabel',
                'urlExpression' => 'Yii::app()->controller->createUrl("view", array("id" => $data["id"]))'
            ),
            array(
                'class' => 'TbEditableColumn',
                'name' => 'id',
                'editable' => array(
                    'url' => $this->createUrl('/p3pages/p3PageTranslation/editableSaver'),
                    //'placement' => 'right',
                )
            ),
            array(
                'name' => 'p3_page_id',
                'value' => 'CHtml::value($data, \'p3Page.itemLabel\')',
                'filter' => '',//CHtml::listData(P3Page::model()->findAll(array('limit' => 1000)), 'id', 'itemLabel'),
            ),
            array(
                'class' => 'TbEditableColumn',
                'name' => 'language',
                'editable' => array(
                    'url' => $this->createUrl('/p3pages/p3PageTranslation/editableSaver'),
                    //'placement' => 'right',
                )
            ),
            array(
                'class' => 'TbEditableColumn',
                'name' => 'menu_name',
                'editable' => array(
                    'url' => $this->createUrl('/p3pages/p3PageTranslation/editableSaver'),
                    //'placement' => 'right',
                )
            ),
            array(
                'class' => 'TbEditableColumn',
                'name' => 'status',
                'editable' => array(
                    'url' => $this->createUrl('/p3pages/p3PageTranslation/editableSaver'),
                    //'placement' => 'right',
                )
            ),
            array(
                'class' => 'TbEditableColumn',
                'name' => 'page_title',
                'editable' => array(
                    'url' => $this->createUrl('/p3pages/p3PageTranslation/editableSaver'),
                    //'placement' => 'right',
                )
            ),
            array(
                'class' => 'TbEditableColumn',
                'name' => 'url_param',
                'editable' => array(
                    'url' => $this->createUrl('/p3pages/p3PageTranslation/editableSaver'),
                    //'placement' => 'right',
                )
            ),
            #'keywords',
            #'description',
            array(
                'class' => 'TbEditableColumn',
                'name' => 'access_owner',
                'editable' => array(
                    'url' => $this->createUrl('/p3pages/p3PageTranslation/editableSaver'),
                    //'placement' => 'right',
                )
            ),
            /*
            array(
                'class' => 'TbEditableColumn',
                'name' => 'access_read',
                'editable' => array(
                    'url' => $this->createUrl('/p3pages/p3PageTranslation/editableSaver'),
                    //'placement' => 'right',
                )
            ),
            array(
                'class' => 'TbEditableColumn',
                'name' => 'access_update',
                'editable' => array(
                    'url' => $this->createUrl('/p3pages/p3PageTranslation/editableSaver'),
                    //'placement' => 'right',
                )
            ),
            array(
                'class' => 'TbEditableColumn',
                'name' => 'access_delete',
                'editable' => array(
                    'url' => $this->createUrl('/p3pages/p3PageTranslation/editableSaver'),
                    //'placement' => 'right',
                )
            ),
            array(
                'class' => 'TbEditableColumn',
                'name' => 'copied_from_id',
                'editable' => array(
                    'url' => $this->createUrl('/p3pages/p3PageTranslation/editableSaver'),
                    //'placement' => 'right',
                )
            ),
            #'created_at',
            #'updated_at',
            */

            array(
                'class' => 'TbButtonColumn',
                'buttons' => array(
                    'view' => array('visible' => 'Yii::app()->user->checkAccess("P3pages.P3PageTranslation.View")'),
                    'update' => array('visible' => 'Yii::app()->user->checkAccess("P3pages.P3PageTranslation.Update")'),
                    'delete' => array('visible' => 'Yii::app()->user->checkAccess("P3pages.P3PageTranslation.Delete")'),
                ),
                'viewButtonUrl' => 'Yii::app()->controller->createUrl("view", array("id" => $data->id))',
                'updateButtonUrl' => 'Yii::app()->controller->createUrl("update", array("id" => $data->id))',
                'deleteButtonUrl' => 'Yii::app()->controller->createUrl("delete", array("id" => $data->id))',
            ),
        )
    )
);
?>
<?php Yii::endProfile('P3PageTranslation.view.grid'); ?>