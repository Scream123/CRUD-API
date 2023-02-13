<a href="/cabinet"><-В личный кабинет</a>

<div class="container">
    <div class="row">
        <div class="col-12">
            <h5 class="modal-title" id="exampleModalLabel">Редактирование опроса</h5>
            <span id="messageShow"></span>
            <form action="" id="valentine" name="valentine">
                <table class="table table-striped table-hover mt-2">

                    <thead class="table-dark">
                    <th>ID</th>
                    <th>Название вопроса</th>
                    <th>Ответы</th>
                    <th>Количество голосов</th>
                    <th>Статус</th>
                    <th>Дата</th>
                    <th>Действия</th>
                    </thead>
                    <tbody class="surveyBodyTable">
                    <?php
                    $rowNumber = 1;
                    $statusData = ['1'=>'Черновик', '2' => 'Опубликовано'];
                    foreach ($surveyData as $key => $data):
                        $rowNumber = 0;
                        ?>
                        <?php
                        foreach ($data['answers'] as $answerId => $answerParam): ?>
                            <tr class="surveysTr surveysTr_<?= $data['survey_id'] ?>" data-id="<?= $data['survey_id'] ?>">
                                <?php
                                if ($rowNumber == 0): ?>
                                    <td class="survey_id"
                                        data-id="<?= $data['survey_id'] ?>" rowspan="<?= count($data['answers']) ?>">
                                        <?= $data['survey_id'] ?>
                                        <span class="messageShow"></span>
                                        <input type="hidden" name="surveyId" value="<?=$data['survey_id']?>">
                                    </td>
                                    <td rowspan="<?= count($data['answers']); ?>">
                                        <label for="surveyTitle">
                                            <input type="text" id="surveyTitle" name="surveyTitle"
                                                   value="<?= $data['surveysTitle'] ?>">
                                        </label>
                                    </td>
                                <?php
                                endif; ?>
                                <td>
                                    <label for="answerTitle">
                                        <input type="text" name="answer[<?= $answerId ?>][title]" class="answer"
                                               data-id="<?= $answerId; ?>"
                                               value="<?= $answerParam['answerTitle'] ?>"/>
                                    </label>
                                </td>
                                <td>
                                    <input type="number" name="answer[<?= $answerId ?>][votes]"
                                           data-id="<?= $answerId; ?>"
                                           value="<?= $answerParam['numberVotes'] ?>">
                                </td>
                                <?php
                                if ($rowNumber == 0): ?>

                                    <td class="status" rowspan="<?= count($data['answers']); ?>">
                                        <select name="status" id="status" aria-label="Default select example"
                                                required>
                                            <?php foreach ($statusData as $statusCode => $statusValue):?>
                                            <option value="<?= $statusCode?>"  <?php echo ($statusCode == $data['status']) ? "selected" :  "" ?>><?= $statusValue ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td class="surveyData" id="date_published"
                                        rowspan="<?= count($data['answers']); ?>">
                                        <?= $data['date_published'] ?>
                                    </td>
                                    <td class="surveyAction" rowspan="<?= count($data['answers']); ?>">
                                        <button type="submit" title="Сохранить" id="updateSurvey"
                                                class="btn btn-success"><i
                                                    class="fa fa-save"></i></button>
                                        <button title="Удалить" class="btn btn-danger surveyDelete">
                                            <i class="fa fa-trash-alt"></i></button>
                                    </td>
                                <?php
                                endif; ?>
                            </tr>
                            <?php
                            $rowNumber++; ?>
                        <?php
                        endforeach; ?>
                    <?php
                    endforeach; ?>
                    </tbody>
                </table>
            </form>

        </div>
    </div>
</div>

