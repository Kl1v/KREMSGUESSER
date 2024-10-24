<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tag der offenen Tür - HTL Krems</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header class="container">
        <div class="logo text-center">
            <img src="htlkrems-logo.png" alt="HTL Krems Logo" style="width: 300px; height: auto;" class="mb-4">
        </div>
        <h1 class="text-center">OPEN DAYS SPIEL</h1>
        <div class="row">
            <div class="mb-5 text-center">
                <h5>Ein zufälliger Wert zwischen 0 und 1000 wird generiert. </h5>
                <h5 class="text-primary">Errate den Wert!</h5>
            </div>
            <div class="col-lg-12 text-center">
                <input type="range" class="container form-range" min="0" max="1000" id="customRange1" disabled>
            </div>
            <div class="col-lg-6">0</div>
            <div class="col-lg-6 text-end">1000</div>
        </div>
    </header>
    <div class="text-center">
        <input type="number" id="userGuess" class="mb-4" placeholder="Gib deinen Wert ein" min="0" max="1000"><br>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"
            id="guessButton" disabled>
            Guess
        </button>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel" style="color: black;">Dein Guess</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Dein Wert: <span id="userValue"></span></p>
                        <p>Richtiger Wert: <span id="correctValue"></span></p>
                        <p>_________________________</p>
                        <p>Dein Score: <span id="score"></span></p>
                        <div class="mt-3">
                            <label for="userName" class="form-label">Dein Name:</label>
                            <input type="text" id="userName" class="form-control" placeholder="Gib deinen Namen ein">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary reload-page"
                            data-bs-dismiss="modal">Abbrechen</button>
                        <button type="button" class="btn btn-primary submit-name">Absenden</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>
    <script>
    $(document).ready(function() {
        var random = Math.floor(Math.random() * 1001);
        document.getElementById("customRange1").value = random;

        $('#userGuess').on('input', function() {
            var userGuess = $('#userGuess').val();
            if (userGuess !== "" && !isNaN(userGuess) && userGuess >= 0 && userGuess <= 1000) {
                $('#guessButton').prop('disabled', false);
            } else {
                $('#guessButton').prop('disabled', true);
            }
        });

        $('#guessButton').click(function() {
            var userGuess = parseInt($('#userGuess').val());
            var x = Math.abs(5 * (random - userGuess));
            var score = Math.max(0, Math.min(1000, 1000 - x));

            $('#userValue').text(userGuess);
            $('#correctValue').text(random);
            $('#score').text(score);
        });

        $('.submit-name').click(function() {
            var name = $('#userName').val();
            var score = $('#score').text();

            if (name && score) {
                $.ajax({
                    type: 'POST',
                    url: 'save_score.php',
                    data: {
                        name: name,
                        score: score
                    },
                    success: function(response) {
                        alert(response);
                        location.reload();
                    },
                    error: function() {
                        alert('Failed to save score');
                    }
                });
            } else {
                alert('Please enter your name');
            }
        });

        $('.reload-page').click(function() {
            location.reload();
        });
    });
    </script>
</body>

</html>