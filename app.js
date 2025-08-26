// js/app.js

// ---- mock data (front-end only for now) ----
const BIKES = [
  { id: 1, name: "Yamaha FZ",      city: "Mumbai", rate: 100 },
  { id: 2, name: "Honda Activa",   city: "Delhi",  rate: 60  },
  { id: 3, name: "Royal Enfield",  city: "Delhi",  rate: 120 },
  { id: 4, name: "Bajaj Pulsar",   city: "Ladakh", rate: 110 },
  { id: 5, name: "TVS Apache",     city: "Mumbai", rate: 90  },
  { id: 6, name: "KTM Duke 250",   city: "Ladakh", rate: 150 }
];

// ---- helpers ----
function $(sel) { return document.querySelector(sel); }
function getParam(name) {
  const url = new URL(window.location.href);
  return url.searchParams.get(name) || "";
}
function unique(arr) { return [...new Set(arr)]; }
function title(s) { return s.charAt(0).toUpperCase() + s.slice(1); }

// ---- landing page: search -> redirect to bikes with query ----
function wireLandingSearch() {
  const form = $("#searchForm");
  const input = $("#searchInput");
  if (!form || !input) return;
  form.addEventListener("submit", (e) => {
    e.preventDefault();
    const q = input.value.trim();
    const url = q ? `bikes.html?query=${encodeURIComponent(q)}` : `bikes.html`;
    window.location.href = url;
  });
}

// ---- bikes page: render list, filters, booking flow ----
function renderBikes(listEl, bikes) {
  if (!listEl) return;
  if (!bikes.length) {
    listEl.innerHTML = `<p>No bikes match your filters.</p>`;
    return;
  }
  listEl.innerHTML = bikes.map(b => `
    <div style="background:rgba(0,0,0,0.6); padding:12px; border-radius:10px; margin:8px 0;">
      <strong>${b.name}</strong> • ${b.city} • ₹${b.rate}/hour
      <div style="margin-top:8px;">
        <button data-book="${b.id}" style="padding:6px 10px; border:0; border-radius:6px; background:#ff4c4c; color:#fff; cursor:pointer;">
          Book
        </button>
      </div>
    </div>
  `).join("");

  // attach booking handlers
  listEl.querySelectorAll("button[data-book]").forEach(btn => {
    btn.addEventListener("click", () => {
      const id = Number(btn.getAttribute("data-book"));
      const bike = BIKES.find(x => x.id === id);
      if (!bike) return;

      const hoursStr = prompt(`Enter number of hours to book the ${bike.name} (₹${bike.rate}/hour):`);
      if (!hoursStr) return;
      const hours = Number(hoursStr);
      if (!Number.isFinite(hours) || hours <= 0) {
        alert("Please enter a valid number of hours.");
        return;
      }

      // simple pricing: hourly rate; GST 18%; refundable security ₹500 (example)
      const base = bike.rate * hours;
      const gst = Math.round(base * 0.18);
      const security = 500;
      const total = base + gst + security;

      alert(
        `Booking Summary\n\n` +
        `Bike: ${bike.name}\n` +
        `City: ${bike.city}\n` +
        `Hours: ${hours}\n` +
        `Base: ₹${base}\n` +
        `GST (18%): ₹${gst}\n` +
        `Refundable Security: ₹${security}\n` +
        `-------------------------\n` +
        `Total Payable Now: ₹${total}\n\n` +
        `Guidelines:\n` +
        `• Valid DL & original ID proof required at pickup.\n` +
        `• Helmet mandatory. Follow all traffic rules.\n` +
        `• Fuel not included unless specified; return with same level.\n` +
        `• Late return fee: ₹200/hour + applicable charges.\n` +
        `• Damage/theft subject to assessment under terms.\n`
      );
    });
  });
}

function wireBikesPage() {
  const listEl = $("#bikeList");
  const citySel = $("#cityFilter");
  const searchInp = $("#searchFilter");
  const clearBtn = $("#clearFilters");
  if (!listEl || !citySel || !searchInp) return;

  // prefill from query param
  const initialQuery = getParam("query");
  if (initialQuery) searchInp.value = initialQuery;

  // populate city dropdown
  const cities = unique(BIKES.map(b => b.city)).sort();
  cities.forEach(c => {
    const opt = document.createElement("option");
    opt.value = c;
    opt.textContent = c;
    citySel.appendChild(opt);
  });

  function applyFilters() {
    const q = searchInp.value.trim().toLowerCase();
    const city = citySel.value.trim();
    const filtered = BIKES.filter(b => {
      const matchesCity = city ? b.city === city : true;
      const matchesQuery = q ? (b.name.toLowerCase().includes(q) || b.city.toLowerCase().includes(q)) : true;
      return matchesCity && matchesQuery;
    });
    renderBikes(listEl, filtered);
  }

  searchInp.addEventListener("input", applyFilters);
  citySel.addEventListener("change", applyFilters);
  clearBtn?.addEventListener("click", () => {
    searchInp.value = "";
    citySel.value = "";
    applyFilters();
  });

  applyFilters();
}

// ---- forms: basic front-end validation only (no PHP yet) ----
function wireLoginForm() {
  const form = $("#loginForm");
  if (!form) return;
  form.addEventListener("submit", (e) => {
    e.preventDefault();
    const email = $("#loginEmail")?.value.trim();
    const pwd = $("#loginPassword")?.value;
    if (!email || !pwd || pwd.length < 6) {
      alert("Enter a valid email and password (min 6 chars).");
      return;
    }
    alert("This is a demo only (no server yet). We'll connect it to MySQL tomorrow.");
  });
}

function wireSignupForm() {
  const form = $("#signupForm");
  if (!form) return;
  form.addEventListener("submit", (e) => {
    e.preventDefault();
    const name = $("#fullName")?.value.trim();
    const email = $("#signupEmail")?.value.trim();
    const pwd = $("#signupPassword")?.value;
    if (!name || !email || !pwd || pwd.length < 6) {
      alert("Please fill all fields. Password must be at least 6 characters.");
      return;
    }
    alert("Signup is demo-only for now. We'll save this to MySQL tomorrow.");
  });
}

// ---- nav active highlight (optional) ----
function highlightActiveNav() {
  const path = location.pathname.toLowerCase();
  document.querySelectorAll(".navbar nav a").forEach(a => {
    const href = a.getAttribute("href")?.toLowerCase() || "";
    if (path.endsWith(href)) a.style.textDecoration = "underline";
  });
}

// ---- init ----
document.addEventListener("DOMContentLoaded", () => {
  wireLandingSearch();
  wireBikesPage();
  wireLoginForm();
  wireSignupForm();
  highlightActiveNav();
});
