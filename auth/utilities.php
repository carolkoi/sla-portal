<?php
  function isAuthenticated() {
      return isset( $_SESSION['user-id']);
  }
  function generateToken($length = 64) {
      // generate random token
      if ($length % 2 !== 0) {
          throw new Exception('$length must be even.');
          return false;
      }
      return bin2hex(random_bytes($length/2));
  }

  function getFullPath($relativePath) {
      $protocol = ( !empty($_SERVER['HTTPS']) &&
          $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443
      ) ? "https://" : "http://";
      $domainName = $_SERVER['HTTP_HOST'];
      $relPathSplit = explode('/', $relativePath);
      $pathFromHost = dirname($_SERVER['REQUEST_URI']);
      $pathFromHostSplit = explode('/', $pathFromHost);
      while ($relPathSplit[0] === '..') {
          array_shift($relPathSplit);
          array_pop($pathFromHostSplit);
      }
      return $protocol.$domainName . implode('/', $pathFromHostSplit) .
          '/' . implode('/', $relPathSplit);
  }


  function logout() {
      unset($_SESSION['user-id']);
      unset($_COOKIE['token']); // unset on server
      setcookie('token', '', 0); // unset on client
  }
?>
