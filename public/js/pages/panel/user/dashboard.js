$(document).ready(function () {
  // Check if Chart.js is loaded
  if (typeof Chart === "undefined") {
    console.error(
      "Chart.js is not loaded. Please include Chart.js before this script."
    );
    return;
  }

  // Function to format numbers with commas
  function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
  }

  // Current month and year for daily chart
  let currentChartDate = new Date();
  currentChartDate.setDate(1); // Set to first day of month

  // Load summary data
  function loadSummary() {
    // Get total products
    $.get("/api/produk", function (products) {
      $(".total-produk").text(formatNumber(products.length || 0));
    }).fail(function () {
      $(".total-produk").text("0");
    });

    // Get total categories
    $.get("/api/kategori", function (categories) {
      $(".total-kategori").text(formatNumber(categories.length || 0));
    }).fail(function () {
      $(".total-kategori").text("0");
    });

    // Get total transactions
    $.get("/api/transaksi", function (transactions) {
      $(".total-tansaksi").text(formatNumber(transactions.length || 0));
      prepareMonthlyChart(transactions);
      prepareDailyChart(transactions); // Prepare daily chart with all transactions
    }).fail(function () {
      $(".total-tansaksi").text("0");
      // Create empty charts when API fails
      renderMonthlyChart([], []);
      renderDailyChart([], []);
    });
  }

  // Prepare monthly chart data (similar to your original function)
  function prepareMonthlyChart(transactions) {
    try {
      if (!transactions || transactions.length === 0) {
        renderMonthlyChart([], []);
        return;
      }

      const monthlyData = {};
      let hasValidData = false;

      transactions.forEach((transaction) => {
        try {
          const date = new Date(transaction.created_at);
          if (isNaN(date.getTime())) throw new Error("Invalid date");

          const monthYear = `${date.getFullYear()}-${(date.getMonth() + 1)
            .toString()
            .padStart(2, "0")}`;

          if (!monthlyData[monthYear]) {
            monthlyData[monthYear] = 0;
          }

          const amount = parseFloat(transaction.total_harga) || 0;
          monthlyData[monthYear] += amount;
          if (amount > 0) hasValidData = true;
        } catch (e) {
          console.warn("Skipping invalid transaction:", transaction, e);
        }
      });

      if (!hasValidData) {
        renderMonthlyChart([], []);
        return;
      }

      // Sort months chronologically
      const sortedMonths = Object.keys(monthlyData).sort();
      const labels = sortedMonths.map((month) => {
        const [year, monthNum] = month.split("-");
        return new Date(year, monthNum - 1).toLocaleDateString("id-ID", {
          month: "short",
          year: "numeric",
        });
      });
      const data = sortedMonths.map((month) => monthlyData[month]);

      renderMonthlyChart(labels, data);
    } catch (error) {
      console.error("Error preparing monthly chart data:", error);
      renderMonthlyChart([], []);
    }
  }

  // Prepare daily chart data for selected month
  function prepareDailyChart(transactions, date = currentChartDate) {
    try {
      if (!transactions || transactions.length === 0) {
        renderDailyChart([], []);
        return;
      }

      // Get year and month from the date
      const year = date.getFullYear();
      const month = date.getMonth();

      // Get number of days in the month
      const daysInMonth = new Date(year, month + 1, 0).getDate();

      // Initialize daily data with 0 for all days
      const dailyData = {};
      for (let day = 1; day <= daysInMonth; day++) {
        dailyData[day] = 0;
      }

      let hasValidData = false;

      // Filter transactions for the selected month and year
      transactions.forEach((transaction) => {
        try {
          const transDate = new Date(transaction.created_at);
          if (isNaN(transDate.getTime())) throw new Error("Invalid date");

          // Check if transaction is in the selected month and year
          if (
            transDate.getFullYear() === year &&
            transDate.getMonth() === month
          ) {
            const day = transDate.getDate();
            const amount = parseFloat(transaction.total_harga) || 0;
            dailyData[day] += amount;
            if (amount > 0) hasValidData = true;
          }
        } catch (e) {
          console.warn("Skipping invalid transaction:", transaction, e);
        }
      });

      if (!hasValidData) {
        renderDailyChart([], []);
        return;
      }

      // Create labels (1, 2, 3, ..., daysInMonth)
      const labels = Array.from({ length: daysInMonth }, (_, i) => i + 1);
      const data = labels.map((day) => dailyData[day]);

      renderDailyChart(labels, data, date);
    } catch (error) {
      console.error("Error preparing daily chart data:", error);
      renderDailyChart([], []);
    }
  }

  // Render monthly chart (similar to your original function)
  function renderMonthlyChart(labels, data) {
    renderChart(
      "monthlyChart",
      "Grafik Transaksi Bulanan",
      labels,
      data,
      "line"
    );
  }

  // Render daily chart
  function renderDailyChart(labels, data, date = currentChartDate) {
    const monthName = date.toLocaleDateString("id-ID", {
      month: "long",
      year: "numeric",
    });

    renderChart(
      "dailyChart",
      `Grafik Transaksi Harian - ${monthName}`,
      labels,
      data,
      "bar",
      true // Add navigation controls
    );
  }

  // Generic chart rendering function
  function renderChart(
    canvasId,
    title,
    labels,
    data,
    chartType,
    withNavigation = false
  ) {
    try {
      // Find or create chart container
      let chartContainer = $(`.${canvasId}-container`);

      // If container doesn't exist, create new one
      if (chartContainer.length === 0) {
        const chartClass =
          canvasId === "dailyChart" ? "daily-chart" : "monthly-chart";

        $(".container").append(`
          <div class="row ${canvasId}-container">
            <div class="col s12">
              <div class="card dashboard-card ${chartClass}">
                <div class="card-content">
                  <h5>${title}</h5>
                  ${
                    withNavigation
                      ? `
                  <div class="chart-navigation">
                    <a href="#" class="prev-month"><i class="material-icons">chevron_left</i></a>
                    <span class="current-month"></span>
                    <a href="#" class="next-month"><i class="material-icons">chevron_right</i></a>
                  </div>
                  `
                      : ""
                  }
                  <div class="chart-wrapper" style="height: 300px;">
                    <canvas id="${canvasId}"></canvas>
                  </div>
                  <div id="${canvasId}Fallback" class="hide"></div>
                </div>
              </div>
            </div>
          </div>
        `);
        chartContainer = $(`.${canvasId}-container`);
      } else {
        // If container exists, clean its content
        chartContainer.find(`#${canvasId}`).remove();
        chartContainer
          .find(".chart-wrapper")
          .append(`<canvas id="${canvasId}"></canvas>`);

        // Update title and month display
        chartContainer.find("h5").text(title);
        if (withNavigation) {
          const monthName = currentChartDate.toLocaleDateString("id-ID", {
            month: "long",
            year: "numeric",
          });
          chartContainer.find(".current-month").text(monthName);
        }
      }

      const chartCanvas = $(`#${canvasId}`)[0];
      const fallbackElement = $(`#${canvasId}Fallback`);

      // Hide fallback
      fallbackElement.addClass("hide");

      if (!chartCanvas) {
        throw new Error("Chart canvas not found");
      }

      // Destroy previous chart if exists
      if (window[canvasId] && typeof window[canvasId].destroy === "function") {
        window[canvasId].destroy();
      }

      // If no data, show message
      if (labels.length === 0 || data.length === 0) {
        fallbackElement.removeClass("hide").html(`
          <div class="chart-message">
            <i class="material-icons">info_outline</i>
            <p>Tidak ada data transaksi yang tersedia untuk ditampilkan dalam grafik.</p>
          </div>
        `);
        return;
      }

      // Create new chart
      window[canvasId] = new Chart(chartCanvas, {
        type: chartType,
        data: {
          labels: labels,
          datasets: [
            {
              label: "Total Transaksi (Rp)",
              data: data,
              backgroundColor:
                canvasId === "dailyChart"
                  ? "rgba(75, 192, 192, 0.2)"
                  : "rgba(54, 162, 235, 0.2)",
              borderColor:
                canvasId === "dailyChart"
                  ? "rgba(75, 192, 192, 1)"
                  : "rgba(54, 162, 235, 1)",
              borderWidth: 2,
              tension: 0.1,
              fill: chartType === "line",
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: "top",
            },
            tooltip: {
              callbacks: {
                label: function (context) {
                  return "Rp " + formatNumber(context.raw.toFixed(2));
                },
              },
            },
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                callback: function (value) {
                  return "Rp " + formatNumber(value);
                },
              },
            },
            x: {
              title: {
                display: canvasId === "dailyChart",
                text: canvasId === "dailyChart" ? "Tanggal" : "",
              },
            },
          },
        },
      });

      // Add event listeners for navigation if this is daily chart
      if (withNavigation) {
        // Update month display
        const monthName = currentChartDate.toLocaleDateString("id-ID", {
          month: "long",
          year: "numeric",
        });
        chartContainer.find(".current-month").text(monthName);

        // Remove previous event listeners to avoid duplicates
        chartContainer.find(".prev-month, .next-month").off("click");

        // Previous month button
        chartContainer.find(".prev-month").on("click", function (e) {
          e.preventDefault();
          currentChartDate.setMonth(currentChartDate.getMonth() - 1);
          $.get("/api/transaksi", function (transactions) {
            prepareDailyChart(transactions, currentChartDate);
          });
        });

        // Next month button
        chartContainer.find(".next-month").on("click", function (e) {
          e.preventDefault();
          currentChartDate.setMonth(currentChartDate.getMonth() + 1);
          // Don't allow future months
          const now = new Date();
          if (currentChartDate > now) {
            currentChartDate = new Date(now.getFullYear(), now.getMonth(), 1);
          }
          $.get("/api/transaksi", function (transactions) {
            prepareDailyChart(transactions, currentChartDate);
          });
        });
      }
    } catch (error) {
      console.error(`Error rendering ${canvasId}:`, error);
      showDataAsTable(labels, data, `${canvasId}Fallback`);
    }
  }

  // Fallback function to display data as a table
  function showDataAsTable(labels, data, fallbackId) {
    const fallbackElement = $(`#${fallbackId}`);

    if (fallbackElement.length === 0) return;

    // Clear previous content
    fallbackElement.empty().removeClass("hide");

    if (labels.length === 0 || data.length === 0) {
      fallbackElement.html(`
        <div class="chart-message">
          <i class="material-icons">error_outline</i>
          <p>Gagal memuat grafik dan tidak ada data yang tersedia.</p>
        </div>
      `);
      return;
    }

    // Create table HTML
    let tableHtml = `
      <div class="table-responsive">
        <table class="striped">
          <thead>
            <tr>
              <th>${
                fallbackId === "dailyChartFallback" ? "Tanggal" : "Bulan"
              }</th>
              <th>Total Transaksi</th>
            </tr>
          </thead>
          <tbody>`;

    // Add rows
    labels.forEach((label, index) => {
      tableHtml += `
        <tr>
          <td>${label}</td>
          <td>Rp ${formatNumber(data[index].toFixed(2))}</td>
        </tr>`;
    });

    tableHtml += `
          </tbody>
        </table>
      </div>`;

    fallbackElement.html(tableHtml);
  }

  loadSummary();
});
