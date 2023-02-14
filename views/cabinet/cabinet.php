<p class="greeting"></p>
<a href="/"><-На главную</a>
<span id="blockFilterMessage"></span>
<div class="container">
    <div class="row">
        <div class="col-12">
            <div id="cabinetHeader" class="col-xs-12 d-flex justify-content">
                <button id="createSurveyBtn" class="btn btn-success mt-2" data-bs-toggle="modal"
                        data-bs-target="#modalCreate"><i
                            class="fa fa-plus"></i>Добавить опрос</button>
                <form>
                <input type="text"  value="" name="surveyTitleFilter" id="surveyTitleFilter" list="surveyTitleList"/>
                <datalist id="surveyTitleList">
                    <?php


                    foreach ($surveyData as $filter): ?>
                        <option><?= $filter['surveysTitle']; ?></option>
                    <?php
                    endforeach; ?>
                </datalist>
                <button class="btn btn-outline-secondary" id="filterSearchTitleBtn" type="button">
                    Отфильтровать по Названию
                </button>

                    <input type="date"    name="surveyDateFilter" id="surveyDateFilter"/>

                    <button class="btn btn-outline-secondary" id="filterSearchDateBtn" type="button">
                        Отфильтровать по Дате
                    </button>
                    <button type="reset" class="btn btn-outline-secondary" id="reset">Сбросить</button>

                <div>
                <select id="statusFilter">
                    <option>Черновик</option>
                    <option>Опубликовано</option>
                </select>
                <button class="btn btn-outline-secondary" id="filterStatusBtn" type="button">
                    Отфильтровать по Статусу
                </button>
                </div>
                </form>
            </div>
            <span class="messageShow"></span>
            <table class="table table-striped table-hover mt-2 tableSurveys">
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
                foreach ($surveyData as $key => $data):
                    $rowNumber = 0;
                    ?>
                    <?php
                    foreach ($data['answers'] as $answerId => $answerParam): ?>
                        <tr class="surveysTr surveysTr_<?= $data['survey_id'] ?>">
                            <?php
                            if ($rowNumber == 0): ?>
                                <td class="survey_id" rowspan="<?= count($data['answers']) ?>"
                                    data-id="<?= $data['survey_id'] ?>">
                                    <?= $data['survey_id'] ?>
                                </td>
                                <td class="surveyTitle"
                                    rowspan="<?= count($data['answers']); ?>">
                                    <?= $data['surveysTitle'] ?>
                                </td>
                            <?php
                            endif; ?>
                            <td class="answerTitle" data-id="<?= $answerId; ?>"><?= $answerParam['answerTitle'] ?></td>
                            <td class="numberVotes" data-id="<?= $answerId; ?>"><?= $answerParam['numberVotes'] ?></td>
                            <?php
                            if ($rowNumber == 0): ?>
                                <td class="status" rowspan="<?= count($data['answers']); ?>">
                                    <?= $data['status'] === 0 ? 'Черновик' : 'Опубликовано'; ?>
                                </td>
                                <td class="surveyData" rowspan="<?= count($data['answers']); ?>">
                                    <?= $data['date_published'] ?>
                                </td>
                                <td class="surveyAction" rowspan="<?= count($data['answers']); ?>">
                                    <button type="submit"
                                            data-id="<?= $data['survey_id']; ?>" title="Редактировать" class="btn btn-success surveyEdit"><i
                                                class="fa fa-edit"></i></button>
                                    <button type="submit" title="Удалить" class="btn btn-danger surveyDelete"><i
                                                class="fa fa-trash-alt"></i></button>
                                </td>
                            <?php
                            endif; ?>
                        </tr>
                        <?php
                        $rowNumber++; ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<!-- Modal create-->
<div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Добавить опрос</h5>
                <span class="messageShow"></span>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post" id="addSurvey" role="form">
                    <div class="form group">
                        <small>Название опроса</small>
                        <input type="text" class="form-control" value="Тестовый вопрос" id="titleSurvey" name="title"
                               required>
                    </div>
                    <small class="form group">Ответы</small>
                    <div class="input-group dynamicInput">
                        <input type="text" placeholder="напишите ответ" name="answer" aria-label="First name"
                               value="Ответ 1" class="form-control answer" required>

                        <input type="number" min="0" placeholder="Укажите кол-во голосов" name="votes"
                               aria-label="Last name" value="0" class="form-control votes" required>
                    </div>
                    <div class="input-group">
                        <input type="text" placeholder="напишите ответ" name="answer" aria-label="First name"
                               value="Ответ 2" class="form-control answer" required>
                        <input type="number" min="0" placeholder="Укажите кол-во голосов" name="votes"
                               aria-label="Last name" value="0" class="form-control votes" required>
                    </div>
                    <div class="input-group">
                        <input type="text" placeholder="напишите ответ" name="answer" aria-label="First name"
                               value="Ответ 3" class="form-control answer" required>
                        <input type="number" placeholder="Укажите кол-во голосов" name="votes" aria-label="Last name"
                               value="0" class="form-control votes" required>
                    </div>
                    <div class="form-group" style="width: 400px">
                        <select class="form-select" name="status" id="status" aria-label="Default select example"
                                required>
                            <option value="0" selected>Черновик</option>
                            <option value="1">Опубликовать</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="date_published" id="date_published"
                               value="<?= date('Y-m-d') ?>">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success mt-2" id="addInput">Добавить поле <i
                            class="fa fa-plus"></i></button>
                <button class="btn btn-danger mt-2" id="deleteInput">Удалить поле <i
                            class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-warning mt-2" id="resetInput">Сбросить</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                <button type="submit" name="add" id="addSurveyBtn" class="btn btn-primary">Сохранить</button>
            </div>
            </form>

        </div>
    </div>
</div>