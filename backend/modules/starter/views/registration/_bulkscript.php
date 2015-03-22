<?php

use yii\helpers\Url;
?>
<script type="text/javascript">
<?php $this->beginBlock('JS_END') ?>
yii.process = (function ($) {
    var _onSearch = false;
    var pub = {
        golferSearch: function () {
            if (!_onSearch) {
                _onSearch = true;
                var $th = $(this);
                setTimeout(function () {
                    _onSearch = false;
                    var data = {
                        id:<?= json_encode($id) ?>,
                        target:$th.data('target'),
                        term: $th.val(),
                    };
                    var target = '#' + $th.data('target');
                    $.get('<?= Url::toRoute(['golfer-search']) ?>', data,
                        function (html) {
                            $(target).html(html);
                        });
                }, 500);
            }
        },
        action: function () {
            var action = $(this).data('action');
            var params = $((action == 'register' ? '#availables' : '#registereds') + ', .golfer-search').serialize();
            var urlRegister   = '<?= Url::toRoute(['register',   'competition_id' => $id]) ?>';
            var urlDeregister = '<?= Url::toRoute(['deregister', 'competition_id' => $id]) ?>';
            $.post(action == 'register' ? urlRegister : urlDeregister,
                   params,
	   function (r) {
                     $('#availables').html(r[0]);
                     $('#registereds').html(r[1]);
                   });
            return false;
        }
    }

    return pub;
})(window.jQuery);
<?php $this->endBlock(); ?>

<?php $this->beginBlock('JS_READY') ?>
$('.golfer-search').keydown(yii.process.golferSearch);
$('a[data-action]').click(yii.process.action);
<?php $this->endBlock(); ?>
</script>
<?php
yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'], yii\web\View::POS_END);
$this->registerJs($this->blocks['JS_READY'], yii\web\View::POS_READY);
