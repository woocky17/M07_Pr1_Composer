<?php


if (isset($_GET["role"])) {
?>
    <h2>Consulta de Reparación</h2>
    <form action="../Controller/ControllerReparation.php" method="POST">
        <input type="hidden" id="role" name="role" value="<?php echo htmlspecialchars($_GET['role']); ?>">

        <label for="reparation_id">UUID de Reparación:</label>
        <input type="text" name="reparation_id" id="reparation_id" placeholder="Introduce el ID" required>
        <br><br>

        <button type="submit" name="action" value="consult">Consultar</button>
    </form>


    <?php
    if ($_GET["role"] === "employee") {
    ?>


        <h2>Inserción de Reparación</h2>
        <form action="../Controller/ControllerReparation.php" method="POST" enctype="multipart/form-data">
            <label for="status">Estado de la Reparación:</label>
            <input type="text" name="status" id="status" placeholder="Introduce el estado" maxlength="255" required>
            <br><br>

            <label for="name">Nombre del Taller:</label>
            <input type="text" name="name" id="name" placeholder="Introduce el nombre del taller" maxlength="12" required>
            <br><br>

            <label for="registerDate">Fecha de Registro:</label>
            <input type="date" name="registerDate" id="registerDate" required>
            <br><br>

            <label for="licensePlate">Placa del Vehículo:</label>
            <input type="text" name="licensePlate" id="licensePlate" placeholder="Formato: 9999-XXX" maxlength="8" pattern="[0-9]{4}-[A-Z]{3}" title="Formato válido: 9999-XXX" required>
            <br><br>

            <label for="photo">Foto:</label>
            <input type="file" name="photo" id="photo" accept="image/*">
            <br><br>

            <button type="submit" name="action" value="insert">Insertar Reparación</button>
        </form>



<?php
    };
};


class ViewReparation
{
    public function render($model)
    {
        if ($model instanceof ErrorMessage) {
            echo "<div class='alert alert-danger' role='alert'>{$model->getMessage()}</div>";
            return;
        }
        if (!$model) {
            echo "<div class='alert alert-danger' role='alert'>Reparación no encontrada.</div>";
            return;
        }

        echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">';

        echo "<div class='container mt-5'>";
        echo "<h1 class='mb-4'>Detalles de la Reparación</h1>";

        echo "<div class='card' style='width: 100%;'>";
        echo "<div class='card-body'>";
        echo "<h3 class='card-text'><strong>Id de la reparacion:</strong> {$model->getId()}</h3>";
        echo "<p class='card-text'><strong>Estado:</strong> {$model->getStatus()}</p>";
        echo "<p class='card-text'><strong>Nombre del Taller:</strong> {$model->getName()}</p>";
        echo "<p class='card-text'><strong>Fecha de Registro:</strong> " . ($model->getRegisterDate() ?: "No disponible") . "</p>";
        echo "<p class='card-text'><strong>Placa del Vehículo Dañado:</strong> " . ($model->getLicensePlate() ?: "No disponible") . "</p>";

        if ($model->getPhoto()) {
            echo "<div class='mb-3'><img src='{$model->getPhoto()}'alt='Foto del vehículo dañado' class='img-fluid' '></div>";
        } else {
            echo "<p><strong>No hay foto disponible.</strong></p>";
        }

        echo "</div>";
        echo "</div>";
        echo "</div>";
    }
}
