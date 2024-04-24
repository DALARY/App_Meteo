const forecastMore = document.getElementById("forecast-more");
const forecast = document.getElementById("forecast");
const cityNameInput = document.getElementById("city-name");
const now = new Date().toISOString().split("T")[0];

forecastMore.addEventListener("click", () => {
  const cityName =
    cityNameInput.textContent == "" ? "Toulouse" : cityNameInput.textContent;
  const action = forecast.classList.contains("forecast") ? "show" : "hide";
  const buttonText = action === "show" ? "Voir moins" : "Voir la semaine";

  forecast.innerHTML = "";

  fetch("http://127.0.0.1:8000/weather/forecast/" + cityName)
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      data.list.forEach((weather) => {
        const weatherDate = new Date(weather.dt_txt);
        const weatherHour = weather.dt_txt.split(" ")[1];
        const weatherDateString = weatherDate.toISOString().split("T")[0];

        if (now !== weatherDateString && weatherHour === "12:00:00") {
          const forecastCard = document.createElement("div");
          forecastCard.classList.add("forecast-card");

          if ("rain" in weather) {
            forecastCard.classList.remove("forecast-card");
            forecastCard.classList.add("forecast-card-rain");
          } else if (weather.weather[0].icon == "01d") {
            forecastCard.classList.remove("forecast-card");
            forecastCard.classList.add("forecast-card-sunny");
          }

          const todayHeader = document.createElement("span");
          todayHeader.classList.add("forecast-day-header");
          todayHeader.textContent = new Date(
            weatherDateString
          ).toLocaleDateString("fr");

          const temperatureCard = document.createElement("div");
          temperatureCard.classList.add("forecast-temperature-card");

          const temperatureImage = document.createElement("div");
          temperatureImage.classList.add("temperature-image");

          const image = document.createElement("img");
          image.src = `https://openweathermap.org/img/wn/${weather.weather[0].icon}@2x.png`;

          const description = document.createElement("p");
          const descriptionWeather = weather.weather[0].description
            .toUpperCase()
            .split(" ");
          descriptionWeather.forEach((word) => {
            description.appendChild(document.createTextNode(word));
            description.appendChild(document.createElement("br"));
          });

          const temperature = document.createElement("p");
          temperature.classList.add("temperature");
          temperature.textContent = Math.round(weather.main.temp);

          temperatureImage.appendChild(image);
          temperatureImage.appendChild(description);
          temperatureCard.appendChild(temperatureImage);
          temperatureCard.appendChild(temperature);
          forecastCard.appendChild(todayHeader);
          forecastCard.appendChild(temperatureCard);
          forecast.appendChild(forecastCard);
        }
      });
    });

  forecast.classList.toggle("forecast");
  forecast.classList.toggle("forecast-show");
  forecastMore.textContent = buttonText;
});
