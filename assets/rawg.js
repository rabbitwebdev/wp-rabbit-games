document.addEventListener("DOMContentLoaded", () => {
    const apiKey = rawgData.apiKey;
    const yearFilter = document.getElementById("release-year-filter");
    const platformSelect = document.getElementById("platform-select");
    const gamesContainer = document.getElementById("upcoming-games");

    let selectedPlatform = "all";
    let selectedYear = yearFilter.value;

    // Fetch platforms
    fetch(`https://api.rawg.io/api/platforms?key=${apiKey}`)
        .then(res => res.json())
        .then(data => {
            data.results.forEach(platform => {
                const option = document.createElement("option");
                option.value = platform.id;
                option.textContent = platform.name;
                platformSelect.appendChild(option);
            });
            fetchGames(); // Fetch after platforms loaded
        });

    // Event listeners
    yearFilter.addEventListener("change", () => {
        selectedYear = yearFilter.value;
        fetchGames();
    });

    platformSelect.addEventListener("change", () => {
        selectedPlatform = platformSelect.value;
        fetchGames();
    });

    function getYearRange() {
        const year = parseInt(selectedYear, 10);
        const start = `${year}-01-01`;
        const end = `${year}-12-31`;
        return `${start},${end}`;
    }

    function fetchGames() {
        gamesContainer.innerHTML = "<p>Loading games...</p>";

        const dateRange = getYearRange();
        let url = `https://api.rawg.io/api/games?key=${apiKey}&dates=${dateRange}&ordering=-added`;

        if (selectedPlatform !== "all") {
            url += `&platforms=${selectedPlatform}`;
        }

        fetch(url)
            .then(res => res.json())
            .then(data => {
                gamesContainer.innerHTML = "";
                if (!data.results.length) {
                    gamesContainer.innerHTML = "<p>No games found.</p>";
                    return;
                }

                data.results.forEach(game => {
                    const platforms = game.platforms?.map(p => p.platform.name).join(", ") || "Unknown";
                      const gameUrl = `${window.location.origin}/game-details/${game.slug}/`;
                    gamesContainer.innerHTML += `
                    <div class="col">    
                    <div class="game game-card card ratio ratio-1x1 rounded-0 bg-dark text-bg-dark" style="height:300px;">
                            <a href="${gameUrl}" class="game-link h-100 bg-dark dark bg-dark text-white" style="text-decoration: none; color: inherit;">
                            <img src="${game.background_image}" alt="${game.name}" class="card-img  w-100 h-100 rounded-0 object-fit-cover">
                            <div class="card-img-overlay">
                            <h3 class="card-title fs-6 fw-light">${game.name}</h3>
                            <p class="date">${game.released || "TBA"}</p>
                            </div>
                            </a>
                        </div>
                        </div>
                    `;
                });
            })
            .catch(err => {
                console.error("Error:", err);
                gamesContainer.innerHTML = "<p>Error loading games.</p>";
            });
    }
});
