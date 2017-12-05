<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentGroup\FormRequest\API2;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Auth\Access\AuthorizationException;

class StoreGroupPostComment extends FormRequest
{
	/**
	 * 创建权限判断
	 * @return [type] [description]
	 */
	public function authorize(): bool
	{
		// TODO
		return true;
	}

	public function rules(): array
	{
		return [
			'content' => 'bail|required|max:255'
		];
	}

	public function messages(): array
	{
		return [
			'content.required' => '没有发送任何内容',
			'content.max' => '评论长度最大为255'
		];
	}
}