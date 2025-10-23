package com.alluven.myapplication.network.dto

data class SalonDTO(
    val id: Int,
    val name: String,
    val phone: String?,
    val address: String?,
    val city: String?,
    val image_url: String?
)
