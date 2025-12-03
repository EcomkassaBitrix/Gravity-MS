<?php

namespace Ecomkassa\Moysklad\SDK\Ecomkassa\Response\MarkVerify;

/**
 * Класс для представления элемента ответа проверки маркировки.
 * 
 * Предназначен для хранения информации о результате проверки одного элемента маркировки,
 * включая статус, код маркировки, ошибки и информацию о запросе.
 */
class MarkVerifyItem
{
    /**
     * Константа успешного статуса маркировки.
     */
    public const MARK_SUCCESS = 'MARK_SUCCESS';

    /**
     * Константа ошибочного статуса маркировки.
     */
    public const MARK_FAILURE = 'MARK_FAILURE';
    
    /**
     * Константа статуса товара, который не проходил обработку
     */
    public const MARK_NOT_APPLICABLE = 'MARK_NOT_APPLICABLE';

    /**
     * Индекс элемента.
     * 
     * @var int|null
     */
    protected ?int $index = null;
    
    /**
     * Название элемента.
     * 
     * @var string|null
     */
    protected ?string $name = null;
    
    /**
     * Статус проверки маркировки.
     * 
     * @var string|null
     */
    protected ?string $status = null;
    
    /**
     * Информация о запросе.
     * 
     * @var RequestInfo|null
     */
    protected ?RequestInfo $requestInfo = null;
    
    /**
     * GS код маркировки.
     * 
     * @var string|null
     */
    protected ?string $gsMarkCode = null;
    
    /**
     * Ошибки проверки.
     * 
     * @var array|null
     */
    protected ?array $errors = null;
    
    /**
     * Предупреждения проверки.
     * 
     * @var array|null
     */
    protected ?array $warnings = null;

    public function __construct(?array $response)
    {
        $this->load($response);
    }

    public function load(?array $response): void
    {
        if (is_array($response)) {
            $index = $response['index'] ?? null;

            if (!is_null($index) && is_numeric($index)) {
                $this->setIndex($index);
            }

            $name = $response['name'] ?? null;

            if (!is_null($name)) {
                $this->setName($name);
            }

            $status = $response['status'] ?? null;

            if (!is_null($status)) {
                $this->setStatus($status);
            }

            $gsMarkCode = $response['gsMarkCode'] ?? null;

            if (!is_null($gsMarkCode)) {
                $this->setGsMarkCode($gsMarkCode);
            }

            $errors = $response['errors'] ?? null;

            if (is_array($errors) || is_null($errors)) {
                $this->setErrors($errors);
            }

            $warnings = $response['warnings'] ?? null;

            if (is_array($warnings) || is_null($warnings)) {
                $this->setWarnings($warnings);
            }

            $requestInfo = $response['requestInfo'] ?? null;

            if (!is_null($requestInfo)) {
                $this->setRequestInfo((new RequestInfo())
                    ->setRequestId($requestInfo['requestId'])
                    ->setTimestamp($requestInfo['timestamp']));
            }
        }
    }
    
    /**
     * Получает индекс элемента.
     * 
     * @return int|null
     */
    public function getIndex(): ?int
    {
        return $this->index;
    }
    
    /**
     * Устанавливает индекс элемента.
     * 
     * @param int|null $index
     * @return static
     */
    public function setIndex(?int $index): static
    {
        $this->index = $index;

        return $this;
    }
    
    /**
     * Получает название элемента.
     * 
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }
    
    /**
     * Устанавливает название элемента.
     * 
     * @param string|null $name
     * @return static
     */
    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }
    
    /**
     * Получает статус проверки маркировки.
     * 
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }
    
    /**
     * Устанавливает статус проверки маркировки.
     * 
     * @param string|null $status
     * @return static
     */
    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }
    
    /**
     * Получает информацию о запросе.
     * 
     * @return RequestInfo|null
     */
    public function getRequestInfo(): ?RequestInfo
    {
        return $this->requestInfo;
    }
    
    /**
     * Устанавливает информацию о запросе.
     * 
     * @param RequestInfo|null $requestInfo
     * @return static
     */
    public function setRequestInfo(?RequestInfo $requestInfo): static
    {
        $this->requestInfo = $requestInfo;

        return $this;
    }
    
    /**
     * Получает GS код маркировки.
     * 
     * @return string|null
     */
    public function getGsMarkCode(): ?string
    {
        return $this->gsMarkCode;
    }
    
    /**
     * Устанавливает GS код маркировки.
     * 
     * @param string|null $gsMarkCode
     * @return static
     */
    public function setGsMarkCode(?string $gsMarkCode): static
    {
        $this->gsMarkCode = $gsMarkCode;

        return $this;
    }
    
    /**
     * Получает ошибки проверки.
     * 
     * @return array|null
     */
    public function getErrors(): ?array
    {
        return $this->errors;
    }
    
    /**
     * Устанавливает ошибки проверки.
     * 
     * @param array|null $errors
     * @return static
     */
    public function setErrors(?array $errors): static
    {
        $this->errors = $errors;

        return $this;
    }
    
    /**
     * Получает предупреждения проверки.
     * 
     * @return array|null
     */
    public function getWarnings(): ?array
    {
        return $this->warnings;
    }
    
    /**
     * Устанавливает предупреждения проверки.
     * 
     * @param array|null $warnings
     * @return static
     */
    public function setWarnings(?array $warnings): static
    {
        $this->warnings = $warnings;

        return $this;
    }
}