<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>
	  
    <h1>All Users</h1>

    <?php if ( isset( $results['errorMessage'] ) ) { ?>
            <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>


    <?php if ( isset( $results['statusMessage'] ) ) { ?>
            <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
    <?php } ?>

          <table>
            <tr>
              <th>Login</th>
              <th>Password</th>
              <th>Activity</th>
            </tr>
            
    <?php foreach ( $results['users'] as $user ) { ?>

            <tr onclick="location='admin.php?action=editUser&amp;userId=<?php echo $user->id?>'">
              <td>
                <?php echo $user->login?>
              </td> 
              <td>
                <?php echo $user->password?>
              </td> 
              <td>
                <?php echo"<input class='active' type='checkbox' " . ($user->activity ? "checked" :"");?>>
              </td>
            </tr>

    <?php } ?>

          </table>

          <p><?php echo $results['totalRows']?> user<?php echo ( $results['totalRows'] != 1 ) ? 's' : '' ?> in total.</p>
          <p><a href="admin.php?action=newUser">Add a New User</a></p>
          
            <script  type="text/javascript">$(".active").on('click', function( event ) {
	  event.preventDefault();})</script>

<?php include "templates/include/footer.php" ?>              