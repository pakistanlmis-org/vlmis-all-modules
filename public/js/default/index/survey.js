
$(document).ready(function () {


   if ($('#yes').is(':checked')) {
        $("#report").show();
    }
    if ($('#dyes').is(':checked')) {
        $("#data_difficulty").show();
    }

    $("#no").click(function () {
        $("#report").hide();
    });
    $("#yes").click(function () {
        $("#report").show();
    });
    $("#dno").click(function () {
        $("#data_difficulty").hide();
    });
    $("#dyes").click(function () {
        $("#data_difficulty").show();
    });
});

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function () {
    $("#register-form").validate({//"id=register-form" in servay.php
        rules: {
            email: {//"name" attribute of mail textbox 
                required: true,
                email: true
            },
            name: {
                required: true

            },
            office: {
                required: true
            },
            cellnumber: {
                required: true
            },
            department: {
                required: true
            },
            data_difficulty: {
                required: true
            },
            report: {
                required: true
            }

        },
        messages: {
            email: {
                required: 'Please enter an Email address.',
                email: 'Please enter a valid Email address'
            },
            name: {
                required: 'Please enter your Name.'

            },
            office: {
                required: 'Please enter your Office Name.'
            },
            cellnumber: {
                required: 'Please enter your Cell number.'
            },
            department: {
                required: 'Please enter your Department.'
            }

        }

    });
});
