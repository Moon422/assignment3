<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="/lib/bootstrap/css/bootstrap.min.css">

    <!-- Noto Sans Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- SwiperJS CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css"/>
    
    <link rel="stylesheet" href="style.css">

    <title>Home</title>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php
        date_default_timezone_set("Asia/Dhaka");
        $api_key = "06924deb1bb335adc10b5fc639e083fe";

        $city_name = "Dhaka";
        $country = "Bangladesh";
       
        $geo_url = "http://api.openweathermap.org/geo/1.0/direct?q={$city_name},{$country_code}&limit=1&appid={$api_key}";

        $geo_content = file_get_contents($geo_url);
        $geo_decoded = json_decode($geo_content)[0];

        $lat = $geo_decoded->lat;
        $lon = $geo_decoded->lon;
       
       
        $weather_url = "http://api.openweathermap.org/data/2.5/forecast?lat={$lat}&lon={$lon}&appid={$api_key}";

        $weather_content = file_get_contents($weather_url);
        $weather_decoded = json_decode($weather_content);
    ?>

    <h1 class="py-5 text-center text-white header-text">Weather Records of 5/6 Days</h1>

    <div class="container">
        <h3 class="py-3">
            Showing Weather Data for <span class="location"><em>
                <?php echo $city_name.", ".$country?>
            </em></span>
        </h3>
    </div>

<?php
    $weather_data_grouped_by_date = array();
    $today = date("d/m/Y", time());

    for ($i=0; $i < $weather_decoded->cnt; $i++) { 
        $date = new DateTime($weather_decoded->list[$i]->dt_txt);
        $weather_decoded->list[$i]->dt = $date;
        $date_key = $date->format("d/m/Y");

        if (array_key_exists($date_key, $weather_data_grouped_by_date)) {
            array_push($weather_data_grouped_by_date[$date_key], $weather_decoded->list[$i]);
        } else {
            $weather_data_grouped_by_date[$date_key] = array($weather_decoded->list[$i]);
        }
    }

    foreach ($weather_data_grouped_by_date as $key => $value) {
?>
    <div class="container py-2 px-5">
        <div class="row">
            <div class="col-11">
                <h5>
                    Weather Records of <span class="weather-record-date"><em><?php
                        // echo $key;
                        if ($key == $today) {
                            echo $key." (Today)";
                        } else {
                            echo $key;
                        }
                    ?></span></em>
                </h5>
            </div>
            <div class="col">
                <button class="btn btn-primary w-100" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo $key ?>" aria-expanded="false" aria-controls="<?php echo $key ?>">
                    Toggle
                </button>
            </div>
        </div>
        <div class="collapse show mt-2" id="<?php echo $key ?>">
            <div class="swiper">
                <div class="swiper-wrapper">
<?php
                foreach($value as $element) {
?>
                    <div class="card swiper-slide">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php
                                    $time = $element->dt->format("H:i A");
                                    echo $time;
                                ?>
                            </h5>
                            <p class="card-text">Weather: <?php
                                echo $element->weather[0]->main;
                            ?></p>
                            <p class="card-text">Temperature: <?php
                                $temp = $element->main->temp - 273.15;
                                $temp_min = $element->main->temp_min - 273.15;
                                $temp_max = $element->main->temp_max - 273.15;
                                echo $temp."°C (Min: ".$temp_min."°C, Max: ".$temp_max."°C)";
                            ?></p>
                            <p class="card-text">Humidity: <?php
                                $humidity = $element->main->humidity;
                                echo $humidity."%";
                            ?></p>
                            <p class="card-text">Wind: <?php
                                $wind_speed = $element->wind->speed;
                                $wind_angle = $element->wind->deg;
                                echo $wind_speed."m/s (Direction: ".$wind_angle."°)";
                            ?></p>
                        </div>
                    </div>
<?php
                }
?>
                </div>
                <div class="swiper-scrollbar"></div>
            </div>
        </div>
    </div>
<?php
    }
?>

    <div class="container-fluid p-5 header-text mt-auto text-center text-white">
        <p>
            Every Hourly Weather Cards Can Be Swipped to View Rest of the Weather Updates.
        </p>
        <p>
            Each of Daily Weather Update Row Visibility Can be Toggle On or Off with the Toggle Button.
        </p>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="/lib/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- SwiperJS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.swiper', {
            slidesPerView: 1,
            spaceBetween: 10,
            breakpoints: {
                768: {
                    slidesPerView: 2,
                    spaceBetween: 30
                },
                992: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                },
            },
            scrollbar: {
                el: ".swiper-scrollbar",
                hide: true,
                draggable: true
            },
            // navigation: {
            //     nextEl: '.swiper-button-next',
            //     prevEl: '.swiper-button-prev',
            // },
        });
    </script>
</body>
</html>