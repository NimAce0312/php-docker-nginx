<form action="" method="post">
    <input type="text" name="name" placeholder="Name" value="<?= $data['name'] ?? "" ?>">
    <input type="email" name="email" placeholder="Email" value="<?= $data['email'] ?? "" ?>">
    <button type="submit">Submit</button>
</form>