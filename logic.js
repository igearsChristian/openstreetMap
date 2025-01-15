var infoPanel = document.getElementById("infoPanel");
var infoText = document.getElementById("infoText");
var infoImage = document.getElementById("infoImage");

function showInfoPanel(text) {
  if (infoPanel.classList.contains("active")) {
    infoPanel.classList.remove("active");
  } else {
    infoText.innerHTML = text;
    if (text == "iGears Technology Ltd") {
      infoImage.src = "./img/igears.jpg";
    } else if (text == "Airside Shopping Mall") {
      infoImage.src = "./img/Airside Shopping Mall.jpeg";
    }
    infoPanel.classList.add("active");
  }
}

var controlPanel = document.getElementById("controlPanel");
var toggleButton = document.getElementById("toggleButton");

// Add an event listener to the toggle button
toggleButton.addEventListener("click", function () {
  controlPanel.classList.toggle("active"); // Toggle the active class
  toggleButton.textContent = controlPanel.classList.contains("active")
    ? "←"
    : "→";
});

function switchMapLayer() {
  var selectedLayer = document.querySelector(
    'input[name="options"]:checked'
  ).value;

  if (selectedLayer === "street") {
    map.removeLayer(satelliteLayer);
    map.addLayer(streetLayer);
  } else {
    map.removeLayer(streetLayer);
    map.addLayer(satelliteLayer);
  }
}

// Event listener for radio buttons
document
  .getElementById("mapSelector")
  .addEventListener("change", switchMapLayer);
