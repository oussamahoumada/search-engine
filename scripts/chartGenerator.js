function printChart(realLen, lenAfterCleaning, diff, chartName, title) {
  console.log(diff, chartName);
  if (typeof chartName == "string") {
    ctx = document.getElementById(chartName);
    title = chartName;
  } else {
    ctx = chartName;
  }
  data = {
    labels: ["real Length", "length after cleaning", "Differenece"],
    datasets: [
      {
        label: "words count ",
        data: [realLen, lenAfterCleaning, diff],
        backgroundColor: [
          "rgb(" +
            Math.random() * 256 +
            "," +
            Math.random() * 256 +
            "," +
            Math.random() * 256 +
            ")",
          "rgb(" +
            Math.random() * 256 +
            "," +
            Math.random() * 256 +
            "," +
            Math.random() * 256 +
            ")",
          "rgb(" +
            Math.random() * 256 +
            "," +
            Math.random() * 256 +
            "," +
            Math.random() * 256 +
            ")",
        ],
        hoverOffset: 4,
      },
    ],
  };
  const options = {
    responsive: true,
    plugins: {
      title: {
        display: true,
        color: "black",
        align: "center",
        position: "top",
        text: title,
      },
    },
  };
  config = {
    type: "doughnut",
    data: data,
    options: options,
  };
  new Chart(ctx, config);
}
function redirect() {
  window.location.href =
    "http://localhost/Paris8/master2/search-engine/views/index.php";
}

function showDialog(param) {
  if (typeof param == "string") {
    ctx = document.getElementById(param);
  } else {
    ctx = param;
  }
  console.log(ctx);
  ctx.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      e.preventDefault();
    }
  });
  ctx.showModal();
}

function closeDialog(param) {
  if (typeof param == "string") {
    ctx = document.getElementById(param);
  } else {
    ctx = param;
  }
  ctx.close();
}