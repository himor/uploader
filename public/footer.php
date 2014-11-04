</div> <!-- content -->

<script>
    $('#signupform').on('submit', function(event){
        e.preventDefault();
        var pasw1 = $('#pasw1').val(),
            pasw2 = $('#pasw2').val();
        if (pasw1 != pasw2) {
            alert('Please input the same password twice!');
            return false;
        }
        return true;
    });
</script>

</body>
</html>