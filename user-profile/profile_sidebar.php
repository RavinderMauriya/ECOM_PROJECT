<!-- profile_sidebar.php -->
<style>
  .sidebar {
    width: 250px;
    background-color: #f5f5f5;
    padding: 20px;
  }

  .sidebar .profile {
    text-align: center;
    margin-bottom: 20px;
  }

  .sidebar .profile img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
  }

  .sidebar .profile h3 {
    margin: 10px 0 0;
  }

  .sidebar ul {
    list-style: none;
    padding: 0;
  }

  .sidebar ul li {
    margin: 10px 0;
    padding: 10px;
    background: #e0e0e0;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.3s;
  }

  .sidebar ul li:hover {
    background-color: #ddd;
    color: #088178;
  }

  .sidebar ul li.active {
    background-color: #088178;
    color: white;
    font-weight: bold;
  }

  @media (max-width: 768px) {
    .sidebar {
      width: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .sidebar ul {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
    }

    .sidebar ul li {
      margin: 5px;
      flex: 1 1 30%;
    }
  }
</style>

<div class="sidebar">
  <?php
    $profilePhoto = !empty($user['profile_photo']) ? '../uploads/' . $user['profile_photo'] : 'default.avif';
  ?>
  <div class="profile">
    <img src="<?= htmlspecialchars($profilePhoto) ?>" alt="Profile Picture">
    <h3><?= htmlspecialchars($user['username']) ?></h3>
  </div>
  <ul>
    <li class="<?= basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : '' ?>">
      <a href="profile.php" style="text-decoration: none; display: block; color: inherit;">Account</a>
    </li>
    <li class="<?= basename($_SERVER['PHP_SELF']) == 'password.php' ? 'active' : '' ?>">
      <a href="password.php" style="text-decoration: none; display: block; color: inherit;">Password</a>
    </li>
    <li class="<?= basename($_SERVER['PHP_SELF']) == 'myorder.php' ? 'active' : '' ?>">
      <a href="myorder.php" style="text-decoration: none; display: block; color: inherit;">My Order</a>
    </li>
  </ul>
</div>
