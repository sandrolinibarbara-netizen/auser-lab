<script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js" integrity="sha256-J8ay84czFazJ9wcTuSDLpPmwpMXOm573OUtZHPQqpEU=" crossorigin="anonymous"></script>
<script src="<?=ROOT?>app/surveyGeneral.js"></script>
<script src="<?=ROOT?>app/modules/partials/components/create-material/new-survey.js"></script>
<script>
    $("#new-survey-questions").sortable({
        update: function(e, ui) {
            $("#new-survey-questions form").each(function(i, elm) {
                $elm = $(elm);
                x = $elm.find('h3');
                x.text(($elm.index("#new-survey-questions form")+1) + '. ' + x.text().split('.')[1].trim())
            });
        }
    });
</script>
<script src="<?=ROOT?>metronic/assets/js/widgets.bundle.js"></script>
<script src="<?=ROOT?>metronic/assets/js/custom/widgets.js"></script>

