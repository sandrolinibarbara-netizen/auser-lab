<script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js" integrity="sha256-J8ay84czFazJ9wcTuSDLpPmwpMXOm573OUtZHPQqpEU=" crossorigin="anonymous"></script>
<script src="<?=ROOT?>app/pollGeneral.js"></script>
<script src="<?=ROOT?>app/modules/partials/components/update-material/update-quiz.js"></script>
<script>
    $("#new-poll-questions").sortable({
        update: function(e, ui) {
            $("#new-poll-questions form").each(function(i, elm) {
                $elm = $(elm);
                x = $elm.find('h3');
                if(!x.text().split('.')[1]) return;
                x.text(($elm.index("#new-poll-questions form")+1) + '. ' + x.text().split('.')[1])
            });
        }
    });
</script>
<script src="<?=ROOT?>metronic/assets/js/widgets.bundle.js"></script>
<script src="<?=ROOT?>metronic/assets/js/custom/widgets.js"></script>

