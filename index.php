<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Игрушки Hola</title>

        <meta charset="UTF-8">
        <meta name="theme-color" content="rgb(255, 255, 255)">
        <meta name="author" content="TOO WebNet">
        <meta name="description" content="RetactorPhoto by WebNet">
        <meta name="keywords" content="RedactorPhoto">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="robots" content="index, follow">

        <link rel="shortcut icon" href="/public/images/almatyholaLogo.png" type="image/png">
        <link rel="stylesheet" href="/public/styles/style.css">
        <link rel="stylesheet" href="/public/styles/mobileStyle.css">
        <link rel="manifest" href="/manifest.json">
        
    </head>

    <body>
        <menu>
            <p class="phone">+7 707 535 6989</p>
        </menu>
        <header>
            <div class="headerContent">
                <h1>Развивающие игрушки от Hola</h1>
                <h2>Доставка до двери совершенно бесплатно!</h2>
            </div>
        </header>
        
        <div class="container">
            <div class="card">
                <div class="cardItem">
                    <img src="public/images/bysiC.png" alt="toys hola" class="imgCard">
                    <div class="textCard">
                        <h4>Бизи Куб</h4>
                        <p>
                            Многогранный куб, каждая сторона которого содержит в себе множество 
                            развивающих элементов, также направленных на развитие у малышей логического мышления. 
                            Данная игрушка отлично подойдет для любознательных малышей!
                        </p>
                        <h4>11 990 тг.</h4>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="cardItem">
                    <img src="public/images/stopH.jpg" alt="toys hola" class="imgCard">
                    <div class="textCard">
                        <h4>Стоп-ходули</h4>
                        <p>
                            Отличный выбор, если Ваш малыш только учится ходить! Вместе с ходунками от 
                            HOLA первые шаги Вашего малыша станут гораздо ярче, интереснее, а главное - быстрее! 
                            Ходунки можно трансформировать в стол с набором развивающих элементов!
                        </p>
                        <h4>19 990 тг.</h4>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="cardItem">
                        <img src="public/images/dumb.png" alt="toys hola" class="imgCard">
                        <div class="textCard">
                            <h4>Барабан от Hola</h4>
                            <p>
                                Барабан от HOLA - очень яркая игрушка для малышей! Яркий и красочный 
                                барабан будет светиться разноцветными светодиодами, что сделает его максимально привлекательным 
                                и интересным для Вашего малыша!
                            </p>
                            <h4>10 990 тг.</h4>
                        </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="card">
                <div class="cardItem">
                    <img src="public/images/house.jpg" alt="toys hola" class="imgCard">
                        <hr>
                    <h4>Барабан от Hola</h4>
                    <p>
                        Барабан от HOLA - очень яркая игрушка для малышей! Яркий и красочный 
                        барабан будет светиться разноцветными светодиодами, что сделает его максимально привлекательным 
                        и интересным для Вашего малыша!
                    </p>
                    <h4>10 990 тг.</h4>
                </div>
            </div>
            <div class="card">
                <div class="cardItem">
                    <img src="public/images/cube.jpg" alt="toys hola" class="imgCard">
                        <hr>
                    <h4>Барабан от Hola</h4>
                    <p>
                        Барабан от HOLA - очень яркая игрушка для малышей! Яркий и красочный 
                        барабан будет светиться разноцветными светодиодами, что сделает его максимально привлекательным 
                        и интересным для Вашего малыша!
                    </p>
                    <h4>10 990 тг.</h4>
                </div>
            </div>
            <div class="card">
                <div class="cardItem">
                    <img src="public/images/fency.jpg" alt="toys hola" class="imgCard">
                        <hr>
                    <h4>Барабан от Hola</h4>
                    <p>
                        Барабан от HOLA - очень яркая игрушка для малышей! Яркий и красочный 
                        барабан будет светиться разноцветными светодиодами, что сделает его максимально привлекательным 
                        и интересным для Вашего малыша!
                    </p>
                    <h4>10 990 тг.</h4>
                </div>
            </div>
        </div>

        <script>
             // Проверка на поддержку service worker
            if('serviceWorker' in navigator) {
                navigator.serviceWorker
                    .register('/sw.js')
                    .then(function() { console.log("Service Worker Registered"); });
            }
        </script>
        <script src="/public/scripts/main.js"></script>
    </body>
</html>