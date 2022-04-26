<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<input type="hidden" id="id" name="id"> <br>
User Name: <br>
<input type="text" id="user_name" name="user_name"> <br>
Email: <br>
<input type="text" id="email" name="email"> <br>
<button type="submit" id="btn">Add</button>
<br>
<p id="success"></p>
<meta name="csrf-token" content="{{ csrf_token() }}">


<table style="color: black">
    <thead>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>
    </thead>
    <tbody>

    </tbody>
</table>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        getStudents();

        function getStudents() {
            $.ajax({
                type: "GET",
                url: "/getStudents",
                dataType: "json",
                success: function (response) {
                    $('tbody').html("");
                    $.each(response.students, function (key, item) {
                        $('tbody').append('<tr id="row' + item.id + '" class="row">\
                                <td>' + item.id + '</td>\
                                <td>' + item.user_name + '</td>\
                                <td>' + item.email + '</td>\
                                <td><button value="' + item.id + '" class="edit" type="submit">Edit</button></td>\
                                <td><button value="' + item.id + '" class="delete"  type="submit">Delete</button></td>\
                            </tr>');
                    });

                }

            });
        }


        $("#btn").click(function () {
            let id = $(this).val();
            console.log(id);
            event.preventDefault();
            // console.log('hi') ;
            let data = {
                'id': $("input[name='id']").val(),
                'user_name': $("input[name='user_name']").val(),
                'email': $("input[name='email']").val()
            };
            console.log(data);
            token();
            if ($(this).text() == 'Add') {
                $.ajax({
                    type: "POST",
                    url: "/storeStudents",
                    data: data,
                    dataType: "json",
                    success: function (response) {
                        successMsg(response);
                        getStudents();
                        clearInputs();
                    }
                })
            }

            else if ($(this).text() == 'Update') {
                $.ajax({
                    type: "PUT",
                    url: "/updateStudent/",
                    data: data,
                    dataType: "json",
                    success: function (data) {
                        getStudents();
                        successMsg(data)
                        clearInputs();
                    }
                })
            }
        });


        //gi popolnuvam inputite so tie od baza za da mozam da editiram
        $(document).on('click', '.edit', function (e) {
            e.preventDefault();
            let id = $(this).val();
            $.ajax({
                type: "GET",
                url: "/editStudent",
                data: {
                    id : id
                },
                success: function (response) {
                    $("#id").val(id);
                    $("#user_name").val(response.student.user_name);
                    $("#email").val(response.student.email);
                    $('#btn').text('Update');
                }
            });
        });


        $(document).on('click', '.delete', function () {
            event.preventDefault();
            let id = $(this).val();
            // console.log(id);
            deleteStudent(id)
        });

        function deleteStudent(id) {
            token();
            $.ajax(
                '/deleteStudent',
                {
                    type: 'DELETE',
                    data: {
                        id: id
                    },
                    success: function (response) {
                        let row = $('#row' + id);
                        row.remove();
                        successMsg(response);
                        clearInputs();
                    },
                }
            )
        }

        function clearInputs() {
            $('#user_name').val('');
            $('#email').val('');
            $('#btn').text('Add');
        }

        function successMsg(data) {
            let p = $("#success");
            p.text(data.message);
        }
        function token() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN':
                    $('meta[name="csrf-token"]').attr('content')
                }
            });
        }
    });

</script>
</body>
</html>

