<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
<header class="flex justify-between p-4">
        <a href="/index.php" id="cars">
            <img src="images/cars.gif" alt="">
        </a>
        <div class="lg:hidden" id="burger-icon">
            <img src="/images/menu.png" alt="Menu">
        </div>
        <div id="sidebar"
            class="shadow-xl fixed top-0 right-0 w-1/3 h-full bg-gray-200  z-50 transform translate-x-full duration-300 ">
            <div class="flex justify-end p-4">
                <button id="close-sidebar" class=" text-3xl">X</button>
            </div>
            <div class="flex flex-col items-center space-y-4 text-white">
                <a href="/index.php" class="text-black text-lg">Blog</a>
                <a href="/pages/profile.php" class="text-black text-lg">Profile</a>
                <a href="/pages/dashboard.php" class="text-black text-lg">Dashboard</a>
                <a href="/pages/logout.php" class="text-red-500 text-lg">Log out</a>
            </div>
        </div>
        <div class="hidden lg:flex justify-center space-x-4">
            <ul class="flex items-center text-sm font-medium text-gray-400 mb-0 ">
                <li>
                    <a href="/index.php" class="hover:underline me-4 md:me-6">Blog</a>
                </li>
                <li>
                    <a href="/pages/profile.php" class="hover:underline me-4 md:me-6">Profile</a>
                </li>
                <li>
                    <a href="/pages/dashboard.php" class="hover:underline me-4 md:me-6">Dashboard</a>
                </li>
                <li>
                    <a href="/pages/logout.php" class= "text-red-500 hover:underline me-4 md:me-6">Log out</a>
                </li>  
            </ul>
        </div>
    </header>

  <div class="flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-800 text-gray-200 min-h-screen">
      <div class="p-4 text-center">
        <h2 class="text-2xl font-bold text-white">Manage</h2>
      </div>
      <nav class="mt-6">
        <button id="articles-btn" class="block w-full px-4 py-2 text-sm hover:bg-blue-600 text-blue-300">Articles</button>
        <button id="users-btn" class="block w-full px-4 py-2 text-sm hover:bg-green-600 text-green-300">Users</button>
        <button id="categories-btn" class="block w-full px-4 py-2 text-sm hover:bg-purple-600 text-purple-300">Categories</button>
      </nav>
    </aside>

    <main class="flex-1 p-6">
      <header class="flex justify-between items-center mb-6">
        <h2 id="section-title" class="text-2xl font-semibold text-gray-800">Welcome to the Dashboard</h2>
      </header>

      <div id="table-container" class="bg-white rounded shadow-md p-4 overflow-x-auto">
        <p class="text-gray-500">Select a section from the sidebar to view data.</p>
      </div>
    </main>
  </div>

  <script>
    const sectionTitle = document.getElementById('section-title');
    const tableContainer = document.getElementById('table-container');

    // Données pour le test
    const data = {
      articles: [
        { id: 1, title: "Article 1", author: "Author A" },
        { id: 2, title: "Article 2", author: "Author B" },
        { id: 3, title: "Article 3", author: "Author C" },
      ],
      users: [
        { id: 1, name: "User A", email: "userA@example.com" },
        { id: 2, name: "User B", email: "userB@example.com" },
        { id: 3, name: "User C", email: "userC@example.com" },
      ],
      categories: [
        { id: 1, name: "Category A", description: "Description A" },
        { id: 2, name: "Category B", description: "Description B" },
        { id: 3, name: "Category C", description: "Description C" },
      ],
    };

    function handleAction(action, type, id) {
      alert(`${action} ${type} with ID: ${id}`);
    }

    function generateTable(dataArray, columns, type) {
      let table = `
        <table class="min-w-full table-auto border-collapse border border-gray-300">
          <thead class="bg-gray-200">
            <tr>
              ${columns.map(col => `<th class="px-4 py-2 text-left text-sm text-gray-600 border border-gray-300">${col}</th>`).join('')}
              <th class="px-4 py-2 text-left text-sm text-gray-600 border border-gray-300">Actions</th>
            </tr>
          </thead>
          <tbody>
            ${dataArray.map(row => `
              <tr class="border-b">
                ${columns.map(col => `<td class="px-4 py-2 text-sm text-gray-700 border border-gray-300">${row[col.toLowerCase()] || ''}</td>`).join('')}
                <td class="px-4 py-2 text-sm text-gray-700 border border-gray-300">
                  <button class="text-blue-500" onclick="handleAction('Edit', '${type}', ${row.id})">Edit</button>
                  <button class="text-red-500 ml-4" onclick="handleAction('Delete', '${type}', ${row.id})">Delete</button>
                </td>
              </tr>
            `).join('')}
          </tbody>
        </table>
      `;
      return table;
    }

    document.getElementById('articles-btn').addEventListener('click', () => {
      sectionTitle.textContent = 'Manage Articles';
      tableContainer.innerHTML = generateTable(data.articles, ['ID', 'Title', 'Author'], 'Article');
    });

    document.getElementById('users-btn').addEventListener('click', () => {
      sectionTitle.textContent = 'Manage Users';
      tableContainer.innerHTML = generateTable(data.users, ['ID', 'Name', 'Email'], 'User');
    });

    document.getElementById('categories-btn').addEventListener('click', () => {
      sectionTitle.textContent = 'Manage Categories';
      tableContainer.innerHTML = generateTable(data.categories, ['ID', 'Name', 'Description'], 'Category');
    });
  </script>
</body>
</html>
