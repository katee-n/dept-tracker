$(document).ready(function() {
    $("#addAmount").submit(function (e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.

        var frm = $(this);

        $.ajax({
            accountId: frm.attr('accountId'),
            deptType: frm.attr('deptType'),
            amount: frm.attr('amount'),
            description: frm.attr('description'),
            data: frm.serialize(),
            success: function (data) {
                console.log('Submission was successful.');
                console.log(this.deptType);
            },
            error: function (data) {
                console.log('An error occurred.');
                console.log(data);
            },
        });

    });
});