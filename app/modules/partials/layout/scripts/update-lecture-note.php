<script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js" integrity="sha256-J8ay84czFazJ9wcTuSDLpPmwpMXOm573OUtZHPQqpEU=" crossorigin="anonymous"></script>
<script src="<?=ROOT?>app/lectureGeneral.js"></script>
<script src="<?=ROOT?>app/modules/partials/components/update-material/update-lecture-note.js"></script>
<script>
    $("#new-lecture-note-questions").sortable({
        update: function(e, ui) {
            $("#new-lecture-note-questions form").each(function(i, elm) {
                $elm = $(elm);
                x = $elm.find('h3');
                if(!x.text().split('.')[1]) return;
                x.text(($elm.index("#new-lecture-note-questions form")+1) + '. ' + x.text().split('.')[1])
            });
        }
    });
</script>
<script src="<?=ROOT?>metronic/assets/js/widgets.bundle.js"></script>
<script src="<?=ROOT?>metronic/assets/js/custom/widgets.js"></script>

