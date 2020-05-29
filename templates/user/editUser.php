<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>
<!--        <?php echo "<pre>";
            print_r($results);
            print_r($data);
        echo "<pre>"; ?> Данные о массиве $results и типе формы передаются корректно-->

        <h1><?php echo $results['pageTitle']?></h1>

        <form action="admin.php?action=<?php echo $results['formAction']?>" method="post">
            <input type="hidden" name="userId" value="<?php echo $results['user']->id ?>">

    <?php if ( isset( $results['errorMessage'] ) ) { ?>
            <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>

            <ul>

              <li>
                <label for="login">User Login</label>
                <input type="text" name="login" id="Login" placeholder="Name of the user" required autofocus maxlength="30" value="<?php echo htmlspecialchars( $results['user']->login )?>" />
              </li>
              <li>
                <label for="password">User password</label>
                <input type="password" name="password" id="password" placeholder="password" required autofocus maxlength="30" value="<?php echo htmlspecialchars( $results['user']->password )?>"/>
              </li>
               <li>
                <label for="Activity">Activity</label>
                <input name="activity" class='activeChange' type='checkbox' value="check" name ='activity' <?php if ($results['user']->activity){echo "checked";} else {echo "";};?> />
              </li>
            </ul>

            <div class="buttons">
              <input type="submit" name="saveChanges" value="Save Changes" />
              <input type="submit" formnovalidate name="cancel" value="Cancel" />
            </div>

        </form>

    <?php if ($results['user']->id) { ?>
          <p><a href="admin.php?action=deleteUser&amp;userId=<?php echo $results['user']->id ?>" onclick="return confirm('Delete This User?')">
                  Delete This User
              </a>
          </p>
         
    <?php } ?>
	  
<?php include "templates/include/footer.php" ?>

              